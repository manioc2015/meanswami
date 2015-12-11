<?php namespace App\Http\Requests\Frontend\User;

use App\Http\Requests\Request;

/**
 * Class UpdateProfileRequest
 * @package App\Http\Requests\Frontend\User
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
		$userId = auth()->user()->id;
		return [
			'name'	=> 'required|max:64',
			'email' 	=> "required|email|max:64|unique:users,email,$userId",
			'username'	=> "required|alpha_dash|max:16|min:4|unique:users,username,$userId"
		];
	}
}