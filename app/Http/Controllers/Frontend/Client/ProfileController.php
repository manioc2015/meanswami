<?php namespace App\Http\Controllers\Frontend\Client;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\User\UserContract;
use App\Http\Requests\Frontend\Client\UpdateProfileRequest;
use App\Models\Client\Client;
/**
 * Class Client\ProfileController
 * @package App\Http\Controllers\Frontend
 */
class ProfileController extends Controller {

	/**
	 * @return mixed
     */
	public function update() {
		$user = auth()->user();
		$client = Client::where('user_id', $user->id)->first();
		return view('frontend.client.profile.update')
			->withClient($client);
	}

	/**
	 * @param UpdateProfileRequest $request
	 * @return mixed
	 */
	public function postSave(UpdateProfileRequest $request) {
		$user = auth()->user();
		$client = Client::where('user_id', $user->id)->first();
		$client->update($request->all());
		return redirect()->route('frontend.dashboard')->withFlashSuccess(trans("strings.profile_successfully_updated"));
	}
}