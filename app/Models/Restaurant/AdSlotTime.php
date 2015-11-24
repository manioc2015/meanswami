<?php namespace App\Models\Restaurant;

use App\Models\BaseModel;

/**
 * Class AdSlotTime
 * @package App\Models\Restaurants\AdSlotTime
 */
class AdSlotTime extends BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'ad_slot_times';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['weekdaynum', 'start_time', 'end_time'];

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