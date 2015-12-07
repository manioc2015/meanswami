<?php namespace App\Models\Client;

use App\Models\BaseModel;
use DB;

/**
 * Class Client
 * @package App\Models\Clients\Client
 */
class Client extends BaseModel {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'clients';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['client_name', 'user_id', 'business_name', 'address1', 'address2', 'city', 'state', 'zipcode', 'country', 'phone1', 'phone2', 'billing_method', 'status'];

	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	/**
	 * For soft deletes
	 *
	 * @var array
	 */
	protected $dates = ['created_at','updated_at','deleted_at'];

	public static function getProperties($id) {
		$sql = "SELECT r.id as restaurant_id, r.name, r.address1, r.city, r.state, r.zipcode, r.country, r.phone, r.max_menu_items, fmain.max_menu_items as franchise_max_menu_items, fmain.id as franchise_id, fmain.franchise_name, fr.id as restaurant_franchise_id
			FROM client_properties cp
			LEFT JOIN restaurants r ON (r.id=cp.property_id AND cp.property_type='Restaurant')
			LEFT JOIN franchises fmain ON (fmain.id=cp.property_id AND cp.property_type='Franchise')
			LEFT JOIN franchises fr ON (r.franchise_id=fr.id)
			WHERE cp.client_id = ?
			AND cp.deleted_at IS NULL AND r.deleted_at IS NULL AND fmain.deleted_at IS NULL
			ORDER BY franchise_name ASC NULLS LAST, restaurant_franchise_id, name, zipcode";
		$properties = DB::select($sql, array($id));
		$ret = array();
		$indexMap = array();
		$i = 0;
		foreach ($properties as $property) {
			if ($property->franchise_id) {
				$indexMap[$property->franchise_id] = $i;
				$ret[$i++] = array("franchise_id" => $property->franchise_id, "franchise_name" => $property->franchise_name, "max_menu_items" => $property->franchise_max_menu_items, "restaurants" => array());
			} else if ($property->restaurant_franchise_id) {
				$index = $indexMap[$property->restaurant_franchise_id];
				$ret[$index]["restaurants"][] = array(
					"id" => $property->restaurant_id,
					"name" => $property->name,
					"address1" => $property->address1,
					"city" => $property->city,
					"state" => $property->state,
					"zipcode" => $property->zipcode,
					"country" => $property->country,
					"phone" => $property->phone
				);
			} else {
				$ret['data']["restaurants"][] = array(
					"franchise_id" => null,
					"franchise_name" => null,
					"id" => $property->restaurant_id,
					"name" => $property->name,
					"address1" => $property->address1,
					"city" => $property->city,
					"state" => $property->state,
					"zipcode" => $property->zipcode,
					"country" => $property->country,
					"phone" => $property->phone,
					"max_menu_items" => $property->max_menu_items
				);				
			}
		}
		return $ret;
	}

}
