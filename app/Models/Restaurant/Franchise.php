<?php namespace App\Models\Restaurant;

use App\Models\BaseModel;
use DB;

/**
 * Class Franchise
 * @package App\Models\Restaurants\Franchise
 */
class Franchise extends BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'franchises';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['franchise_name', 'max_menu_items'];

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * For soft deletes
	 *
	 * @var array
	 */
	protected $dates = ['created_at','updated_at','deleted_at'];

	public function clients() {
		return $this->morphMany('App\Models\ModelsToModels\ClientProperties', 'properties');
	}

	public static function getOwnerClientIds($id) {
		$sql = "SELECT DISTINCT cp.client_id
			FROM franchises f
			INNER JOIN client_properties cp ON (f.id=cp.property_id AND cp.property_type='Franchise')
			WHERE f.id = ? AND cp.deleted_at IS NULL";
		$res = DB::select($sql, array($id));
		$ret = array();
		foreach ($res as $row) {
			$ret[$row->client_id] = true;
		}
		return $ret;
	}
}
