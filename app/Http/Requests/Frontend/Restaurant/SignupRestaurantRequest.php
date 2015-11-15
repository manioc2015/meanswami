<?php namespace App\Http\Requests\Frontend\Restaurant;

use App\Http\Requests\Request;

/**
 * Class SignupRestaurantRequest
 * @package App\Http\Requests\Frontend\Restaurant
 */
class SignupRestaurantRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'restaurant_data.name'	=> 'required',
			'restaurant_data.address1'	=> 'required',
			'restaurant_data.city'	=> 'required',
			'restaurant_data.state'	=> 'required',
			'restaurant_data.zipcode'	=> 'required',
			'restaurant_data.country'	=> 'required',
			'restaurant_data.phone'	=> 'required',
		];
	}
}