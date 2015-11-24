<?php namespace App\Models\Restaurant;

use App\Models\BaseModel;
use DB;

/**
 * Class MenuItem
 * @package App\Models\Restaurants\MenuItem
 */
class MenuItem extends BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'menu_items';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['property_id', 'property_type', 'name', 'tagline', 'main_ingredients', 'active', 'is_test_item'];

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

	public static function getOwnerClientIds($id) {
		$sql = "SELECT DISTINCT cp.client_id
			FROM menu_items mi
			INNER JOIN client_properties cp ON (mi.property_id=cp.property_id AND mi.property_type=cp.property_type)
			WHERE mi.id = ? AND cp.deleted_at IS NULL";
		$res = DB::select($sql, array($id));
		$ret = array();
		foreach ($res as $row) {
			$ret[$row['client_id']] = true;
		}
		return $ret;
	}
}