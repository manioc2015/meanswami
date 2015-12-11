<?php namespace App\Http\Requests\Frontend\Access;

use App\Http\Requests\Request;

/**
 * Class RegisterRequest
 * @package App\Http\Requests\Frontend\Access
 */
class RegisterRequest extends Request {

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
			'name' 		=> 'required|max:64',
			'email' 	=> 'required|email|max:64|unique:users',
			'username'	=> 'required|max:16|min:4|unique:users',
			'password'  => 'required|confirmed|min:6',
			'g-recaptcha-response' => 'required|captcha'
		];
	}
}