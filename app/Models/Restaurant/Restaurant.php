<?php namespace App\Models\Restaurant;

use App\Models\BaseModel;
use DB;

/**
 * Class SPRestaurants
 * @package App\Models\Restaurants\SPRestaurants
 */
class Restaurant extends BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'restaurants';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['franchise_id', 'sp_listing_id', 'yp_listing_id', 'yelp_listing_id', 'name', 'address1', 'address2', 'cross_streets', 'city', 'state', 'zipcode', 'country', 'lat', 'lon', 'phone', 'website', 'description', 'email', 'open_hours', 'payment_methods', 'timezone', 'is_claimed_on_yelp', 'status', 'delivers'];

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
			FROM restaurants r
			INNER JOIN client_properties cp ON (r.id=cp.property_id AND cp.property_type='Restaurant')
			WHERE r.id = ? AND cp.deleted_at IS NULL";
		$res = DB::select($sql, array($id));
		$ret = array();
		foreach ($res as $row) {
			$ret[$row->client_id] = true;
		}
		return $ret;
	}
}
