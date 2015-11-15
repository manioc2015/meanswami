<?php namespace App\Models\Restaurants;

use App\Models\BaseModel;

/**
 * Class SPRestaurants
 * @package App\Models\Restaurants\SPRestaurants
 */
class Restaurants extends BaseModel {

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
	protected $fillable = ['franchise_id', 'sp_listing_id', 'yp_listing_id', 'yelp_listing_id', 'name', 'address1', 'address2', 'cross_streets', 'city', 'state', 'zipcode', 'country', 'lat', 'lon', 'phone', 'website', 'description', 'email', 'open_hours', 'payment_methods', 'timezone', 'is_claimed_on_yelp', 'status'];

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
}
