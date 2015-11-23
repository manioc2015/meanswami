<?php namespace App\Http\Requests\Frontend\Restaurant;

use App\Http\Requests\Request;

/**
 * Class SignupClientRequest
 * @package App\Http\Requests\Frontend\Restaurant
 */
class SignupClientRequest extends Request {

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
			'client_data.client_name'	=> 'required',
			'client_data.business_name'	=> 'required',
			'client_data.address1'	=> 'required',
			'client_data.city'	=> 'required',
			'client_data.state'	=> 'required',
			'client_data.zipcode'	=> 'required',
			'client_data.country'	=> 'required',
			'client_data.phone1'	=> 'required',
		];
	}
}