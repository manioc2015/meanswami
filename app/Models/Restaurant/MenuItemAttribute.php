<?php namespace App\Models\Restaurant;

use App\Models\BaseModel;

/**
 * Class MenuItemAttribute
 * @package App\Models\Restaurants\MenuItemAttribute
 */
class MenuItemAttribute extends BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'menu_item_attribute';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['menu_item_id', 'attribute_id', 'attribute_group_id'];

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