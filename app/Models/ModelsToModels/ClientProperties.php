<?php namespace App\Models\ModelsToModels;

use App\Models\BaseModel;

/**
 * Class ClientProperties
 * @package App\Models\ModelsToModels\ClientProperties
 */
class ClientProperties extends BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'client_properties';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['client_id', 'property_id', 'property_type'];

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

	public function properties() {
		return $this->morphTo();
	}

}
