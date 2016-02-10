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
	protected $fillable = ['property_id', 'property_type', 'name', 'tagline', 'main_ingredients', 'active', 'is_test_item', 'availability'];

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
			$ret[$row->client_id] = true;
		}
		return $ret;
	}

	public static function getByProperty($id, $type, $client_id, $activeOnly = true) {
		$activeSql = $activeOnly ? " AND mi.active=TRUE" : "";
		$sql = "SELECT mi.id, mi.name, mi.tagline, mip.min_price, mip.max_price, mi.created_at, mi.availability, mi.active, COALESCE(f.franchise_name, r.name) AS franchise_or_restaurant_name
			FROM menu_items mi INNER JOIN client_properties cp ON (cp.property_id=mi.property_id AND cp.property_type=mi.property_type AND cp.deleted_at IS NULL)
			INNER JOIN menu_item_prices mip ON (mi.id=mip.menu_item_id AND mip.deleted_at IS NULL)
			LEFT JOIN restaurants r ON (r.id=mi.property_id AND mi.property_type='Restaurant' AND r.deleted_at IS NULL)
			LEFT JOIN franchises f ON (f.id=mi.property_id AND mi.property_type='Franchise')
			WHERE mi.property_id=? AND mi.property_type=? AND cp.client_id=? AND mi.deleted_at IS NULL $activeSql
			ORDER BY mi.active DESC, mi.updated_at DESC";
		$res = DB::select($sql, array($id, $type, $client_id));
		foreach ($res as &$row) {
			$row->availability = json_decode($row->availability, true);
		}
		return $res;
	}

	public static function getCountByClient($client_id) {
		$sql = "SELECT cp.property_id, cp.property_type, COUNT(DISTINCT mi.id) FILTER (WHERE mi.active=TRUE) AS active, COUNT(DISTINCT mi.id) AS total 
		FROM client_properties cp LEFT JOIN menu_items mi ON (mi.property_type=cp.property_type AND mi.property_id=cp.property_id AND cp.deleted_at IS NULL)
		WHERE cp.client_id = ? AND mi.deleted_at IS NULL
		GROUP BY cp.property_id, cp.property_type
		ORDER BY cp.property_type, cp.property_id";
		$res = DB::select($sql, array($client_id));
		$ret = array();
		foreach ($res as $row) {
			$ret[$row->property_type][$row->property_id]['active'] = $row->active;
			$ret[$row->property_type][$row->property_id]['total'] = $row->total;
		}
		return $ret;
	}

	public static function getNumActive($id, $type) {
		$sql = "SELECT COUNT(DISTINCT mi.id) AS cnt 
		FROM menu_items mi
		WHERE mi.property_id = ? AND mi.property_type = ? AND mi.deleted_at IS NULL AND mi.active=TRUE";
		$res = DB::select($sql, array($id, $type));
		return $res[0]->cnt;	
	}
}