<?php namespace App\Services;

use App\Models\Access\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'alias' => 'required|max:16|min:4',
			'name' => 'required|max:64|min:4',
			'email' => 'required|email|max:64|unique:users',
			'password' => 'required|confirmed|min:6',
			'g-recaptcha-response' => 'required|captcha'
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		return User::create([
			'alias' => empty($data['alias']) ? null : $data['alias'],
			'name' => $data['name'],
			'email' => empty($data['email']) ? null : $data['email'],
			'password' => bcrypt($data['password']),
		]);
	}

}
