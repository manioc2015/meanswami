<?php namespace App\Models\Restaurant;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SPRestaurants
 * @package App\Models\Restaurants\SPRestaurants
 */
class SPRestaurant extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sp_restaurants';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['sp_listing_id', 'name', 'address1', 'address2', 'city', 'state', 'zipcode', 'country', 'lat', 'lon', 'phone', 'category'];

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
	protected $dates = ['active_datetime'];
}
