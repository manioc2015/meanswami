<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\User\UserContract;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use Session;
/**
 * Class ProfileController
 * @package App\Http\Controllers\Frontend
 */
class ProfileController extends Controller {

	/**
	 * @return mixed
     */
	public function edit() {
		return view('frontend.user.profile.edit')
			->withUser(auth()->user());
	}

	/**
	 * @param UserContract $user
	 * @param UpdateProfileRequest $request
	 * @return mixed
	 */
	public function update(UserContract $user, UpdateProfileRequest $request) {
		$user->updateProfile($request->all());
		$userId = auth()->user()->id;
        $client = \App\Models\Client\Client::where('user_id', $userId)->first();
		$url = $client ? '/dashboard' : '/';
		if (Session::pull('redirectToSave', false)) {
    	    $url =  '/restaurant/signup/save';
    	}
		return redirect($url)->withFlashSuccess(trans("strings.profile_successfully_updated"));
	}
}