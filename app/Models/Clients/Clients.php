<?php namespace App\Models\Clients;

use App\Models\BaseModel;

/**
 * Class Client
 * @package App\Models\Clients\Client
 */
class Clients extends BaseModel {
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

}
