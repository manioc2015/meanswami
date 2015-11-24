<?php namespace App\Models\Restaurant;

use App\Models\BaseModel;

/**
 * Class AdSlotTime
 * @package App\Models\Restaurants\AdSlotTime
 */
class AdSlot extends BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ad_slots';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['restaurant_id', 'priority'];

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