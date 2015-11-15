<?php namespace App\Http\Controllers\Frontend\Restaurant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use App\Models\Clients\Clients;
use App\Models\Restaurants\Restaurants;
use App\Models\ModelsToModels\ClientProperties;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Session;
use DB;

/**
 * Class ManageController
 * @package App\Http\Controllers\Frontend\Restaurant
 */
class ManageController extends Controller {
	use Helpers;
	public function __construct(Guard $auth) {
		$this->auth = $auth;
	}

	public function getIndex() {
		$user = auth()->user();
		if ($user) {
			$client = Clients::where('user_id', $user->id)->first();
			if ($client) {
				$client = $client->first();
			}
			if (Session::has('client_details')) {
				$clientDetails = Session::pull('client_details');
				$clientDetails['user_id'] = $user->id;
				if (!$client) {
					$client = Clients::create($clientDetails);
				} else {
					unset($clientDetails['billing_method']);
					unset($clientDetails['status']);
					$client->update($clientDetails);
				}
				$client->save();
			}
			if (Session::has('restaurant_details')) {
				$restaurantDetails = Session::pull('restaurant_details');
				$restaurant = Restaurants::create($restaurantDetails);
				$restaurant->save();
				$clientProperty = ClientProperties::create(array('client_id' => $client->id, 'property_id' => $restaurant->id, 'property_type' => 'RESTAURANT'));
				$clientProperty->save();
			}
		}
	}

}
