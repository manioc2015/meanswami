<?php namespace App\Http\Controllers\Frontend\Restaurant;
require_once(APP_PATH . 'app/Etc/OAuth.php');
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Restaurant\SignupRestaurantRequest;
use App\Http\Requests\Frontend\Restaurant\SignupClientRequest;
use Illuminate\Http\Request;
use App\Models\Restaurants\SPRestaurants;
use Illuminate\Contracts\Auth\Guard;
use App\Models\Clients\Clients;
use Dingo\Api\Routing\Helpers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Session;
use DB;

/**
 * Class SignupController
 * @package App\Http\Controllers\Frontend\Restaurant
 */
class SignupController extends Controller {
	use Helpers;
	public function __construct(Guard $auth) {
		$this->auth = $auth;
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getLookup()
	{
		return view('frontend.restaurant.add-restaurant');
	}

	public function postLookup(Request $request)
	{
		$phone_number = $request->input('phone_number');
		$phone_number = preg_replace("/[^0-9]/", "", $phone_number);
		$invalidNumber = false;
		if (strlen($phone_number) == 11 && substr($phone_number, 0, 1) == '1') {
		} else if (strlen($phone_number) == 10) {
			$phone_number = '1' . $phone_number;
		} else {
			$invalidNumber = true;
		}
		if (!$invalidNumber) {
			$GLOBALS['CONSUMER_KEY'] = env('YELP_CONSUMER_KEY', NULL);
			$GLOBALS['CONSUMER_SECRET'] = env('YELP_CONSUMER_SECRET', NULL);
			$GLOBALS['TOKEN'] = env('YELP_TOKEN', NULL);
			$GLOBALS['TOKEN_SECRET'] = env('YELP_TOKEN_SECRET', NULL);
			$API_HOST = 'api.yelp.com';
			$category = "restaurants";
			$SEARCH_PATH = "/v2/phone_search?category=$category&phone=$phone_number";
            try {
                $results = json_decode(makeRequest($API_HOST, $SEARCH_PATH), true);
                $results['success'] = true;
            } catch (YelpOAuthException $e) {
                $results = array('success' => false, 'error' => 'Error while connecting to the Yelp API. (' . $e->getMessage() . ')');
            }
            if (count($results) == 0) {
            	$category = "food";
				$SEARCH_PATH = "/v2/phone_search?category=$category&phone=$phone_number";
	            try {
	                $results = json_decode(makeRequest($API_HOST, $SEARCH_PATH), true);
	                $results['success'] = true;
	            } catch (YelpOAuthException $e) {
	                $results = array('success' => false, 'error' => 'Error while connecting to the Yelp API. (' . $e->getMessage() . ')');
	            }
            }
            if ($results['total'] > 0) {
				$data = array();
				$i = 0;
				foreach ($results['businesses'] as $i => $listing) {
					if (!$listing['is_closed']) {
						$data[$i]['phone'] = substr($phone_number, 1, 3) . '-' . substr($phone_number, 4, 3) . '-' . substr($phone_number, 7, 4);
						$data[$i]['name'] = $listing['name'];
						$data[$i]['sp_listing_id'] = '';
						$data[$i]['yelp_listing_id'] = $listing['id'];
						$data[$i]['is_claimed_on_yelp'] = $listing['is_claimed'];
						$signedUp = DB::table('restaurants')->where('phone', $data[$i]['phone']);
						$signedUp = $this->buildRestaurantNameWhere($signedUp, $data[$i]['name']);
						$signedUp = $signedUp->first();
						$data[$i]['signed_up'] = $signedUp ? true : false;
						$data[$i]['address1'] = $listing['location']['address'][0];
						$data[$i]['address2'] = isset_or($listing['location']['address'][1], '');
						$data[$i]['cross_streets'] = isset_or($listing['location']['cross_streets'], '');
						$data[$i]['city'] = $listing['location']['city'];
						$data[$i]['state'] = $listing['location']['state_code'];
						$data[$i]['zipcode'] = $listing['location']['postal_code'];
						$data[$i]['country'] = $listing['location']['country_code'];
						$data[$i]['lat'] = $listing['location']['coordinate']['latitude'];
						$data[$i]['lon'] = $listing['location']['coordinate']['longitude'];
						$i++;
					}
				}
				return $this->response->array(array('data' => $data));
            } else {
				$phone_number = substr($phone_number, 1, 3) . '-' . substr($phone_number, 4, 3) . '-' . substr($phone_number, 7, 4);
				$results = SPRestaurants::where('phone', $phone_number)->get();
				if (count($results) > 0) {
					$data = array();
					$i = 0;
					foreach ($results as $i => $listing) {
						$data[$i]['phone'] = $listing->phone;
						$data[$i]['name'] = $listing->name;
						$data[$i]['sp_listing_id'] = $listing->sp_listing_id;
						$data[$i]['yelp_listing_id'] = '';
						$data[$i]['is_claimed_on_yelp'] = null;
						$signedUp = DB::table('restaurants')->where('phone', $data[$i]['phone']);
						$signedUp = $this->buildRestaurantNameWhere($signedUp, $data[$i]['name']);
						$signedUp = $signedUp->first();
						$data[$i]['signed_up'] = $signedUp ? true : false;
						$data[$i]['address1'] = $listing->address1;
						$data[$i]['address2'] = $listing->address2;
						$data[$i]['city'] = $listing->city;
						$data[$i]['state'] = $listing->state;
						$data[$i]['zipcode'] = $listing->zipcode;
						$data[$i]['country'] = $listing->country;
						$data[$i]['lat'] = $listing->lat;
						$data[$i]['lon'] = $listing->lon;
						$i++;
					}
					return $this->response->array(array('data' => $data));
				} else {

					return $this->response->error('', 404);
				}
			}
		} else {
			return $this->response->error('', 404);
		}
	}

	public function postAddRestaurant(SignupRestaurantRequest $request) {
		$restaurantDetails = $request->input('restaurant_data');
		$restaurantDetails['franchise_id'] = 0;
		$restaurantDetails['yp_listing_id'] = 0;
		$restaurantDetails['email'] = '';
		$restaurantDetails['open_hours'] = '';
		$restaurantDetails['payment_methods'] = '';
		$tzData = DB::table('zip_code')->where('zip_code', $restaurantDetails['zipcode'])->first();
		$restaurantDetails['timezone'] = $tzData->time_zone;
		$restaurantDetails['status'] = 'PENDING_APPROVAL';

		$user = auth()->user();
		$client = null;
		if ($user) {
			$client = Clients::where('user_id', $user->id)->first();
		}
		Session::put('restaurant_details', $restaurantDetails);
		if ($user && $client) {
			return $this->response->array(array('action' => 'redirect', 'url' => '/restaurant/manage'));
		} else {
			return $this->response->array(array('action' => 'newClient'));
		}
	}

	public function postAddClient(SignupClientRequest $request) {
		$user = auth()->user();
		$clientDetails = $request->input('client_data');
		$clientDetails['billing_method'] = 'FREE_BASIC';
		$clientDetails['status'] = 'ACTIVE';
		Session::put('client_details', $clientDetails);
		if ($user) {
			return $this->response->array(array('action' => 'redirect', 'url' => '/restaurant/manage'));
		} else {
			Session::put('redirectToManage', true);
			return $this->response->array(array('action' => 'redirect', 'url' => '/auth/register'));
		}
	}

	private function buildRestaurantNameWhere($db, $name) {
		$words = explode(" ", $name);
		$db->where("name", "ilike", $words[0]." %");
		for ($i=1; $i<count($words)-1; $i++) {
			$db->where("name", "ilike", "% ".$words[$i]." %");
		}
		$db->where("name", "ilike", "% ".$words[$i]);
		return $db;
	}
}
