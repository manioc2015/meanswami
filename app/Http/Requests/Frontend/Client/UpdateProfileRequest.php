<?php namespace App\Http\Requests\Frontend\Client;

use App\Http\Requests\Request;

/**
 * Class SignupClientRequest
 * @package App\Http\Requests\Frontend\Restaurant
 */
class UpdateProfileRequest extends Request {

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
			'client_name'	=> 'required',
			'business_name'	=> 'required',
			'address1'	=> 'required',
			'city'	=> 'required',
			'state'	=> 'required',
			'zipcode'	=> 'required',
			'country'	=> 'required',
			'phone1'	=> 'required',
		];
	}
}