<?php namespace App\Http\Requests\Frontend\Restaurant;

use App\Http\Requests\Request;

/**
 * Class SignupRequest
 * @package App\Http\Requests\Frontend\Restaurant
 */
class SignupLookupRequest extends Request {

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
			'phone_number'	=> 'required'
		];
	}
}