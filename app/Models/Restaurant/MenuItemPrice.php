<?php namespace App\Models\Restaurant;

use App\Models\BaseModel;

/**
 * Class MenuItemPrice
 * @package App\Models\Restaurants\MenuItemPrice
 */
class MenuItemPrice extends BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'menu_item_prices';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['menu_item_id', 'min_price', 'max_price'];

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