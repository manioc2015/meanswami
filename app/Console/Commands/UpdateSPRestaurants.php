<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Restaurant\SPRestaurant;
use DB;

class UpdateSPRestaurants extends Command
{
    private $client_id = 'czjxh2a1x2nuz8ih8bny5xvf0';

    private $secret = 'OYuyj-woUsT9SIoHGwReaQJZSgdEljTHLpPv8kogn4Y';

    private $script_path = '/var/www/swami/public/sp-updatedSince.py';

    private $tmp_file = '/var/www/swami/storage/tmp/splocs_%DATE%.csv';

    private $valid_categories = array('Restaurant' => 1, 'Bakery' => 1, 'CoffeeShop' => 1, 'Bars & Clubs' => 1, 'Food & Beverage Retail' => 1);
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sp-restaurants:update {start-date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the SinglePlatform restaurants table with updated entries..';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(3600);
        ini_set('memory_limit', '512M');
        $existing_categories = array();
        $this->line('Downloading latest changes...');
        $this->tmp_file = str_replace('%DATE%', $this->argument('start-date'), $this->tmp_file);
        $command = $this->script_path . ' -c ' . $this->client_id . ' -s ' . $this->secret . '-u "' . $this->argument('start-date') . '" > ' . $this->tmp_file;
        exec($command);
        $this->line('Importing file...');
        $input = file($this->tmp_file);
        foreach ($input as $i => $row) {
            if ($i == 0) {
                continue;
            }
            $fields = explode(',', trim($row));
            $category = trim($fields[11]);
            $sql = '';
            if (!isset($existing_categories[$category])) {
                $category_db = DB::connection()->getPdo()->quote($category);
                $sql = "INSERT INTO sp_categories (name) SELECT " . $category_db . " WHERE NOT EXISTS (SELECT name FROM sp_categories WHERE name = " . $category_db . ")";
                DB::insert($sql);
                $existing_categories[$category] = 1;
            }
            if (isset($this->valid_categories[$category])) {
                $fields[8] = trim($fields[8]);
                $fields[9] = trim($fields[9]);
                $fields[13] = trim($fields[13]);
                $mapped = array(
                    'sp_listing_id' => trim($fields[0]),
                    'name' => trim($fields[1]),
                    'address1' => trim($fields[2]),
                    'address2' => trim($fields[3]),
                    'city' => trim($fields[4]),
                    'state' => trim($fields[5]),
                    'zipcode' => trim($fields[6]),
                    'country' => trim($fields[7]),
                    'lat' => nonblank_or($fields[8], 0),
                    'lon' => nonblank_or($fields[9], 0),
                    'phone' => trim($fields[10]),
                    'category' => trim($fields[11]),
                    'created_at' => nonblank_or($fields[13], date('Y-m-d H:i:s')),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'deleted_at' => trim($fields[12]) == 'f' ? null : date('Y-m-d H:i:s')
                );
                $this->line(print_r($mapped, true));
                $record = SPRestaurant::firstOrNew(array('sp_listing_id' => $mapped['sp_listing_id']));
                foreach ($mapped as $field => $val) {
                    $record->$field = $val;
                }
                $record->save();
            }
        }
    }
}
