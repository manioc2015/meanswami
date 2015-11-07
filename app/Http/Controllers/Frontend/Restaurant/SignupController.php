<?php namespace App\Http\Controllers\Frontend\Restaurant;
require_once(APP_PATH . 'app/Etc/OAuth.php');
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Restaurant;
use Dingo\Api\Routing\Helpers;

/**
 * Class SignupController
 * @package App\Http\Controllers\Frontend\Restaurant
 */
class SignupController extends Controller {
	use Helpers;
	/**
	 * @return \Illuminate\View\View
	 */
	public function getLookup()
	{
		return view('frontend.restaurant.lookup');
	}

	public function postLookup(\App\Http\Requests\Frontend\Restaurant\SignupLookupRequest $request)
	{
		$GLOBALS['CONSUMER_KEY'] = env('YELP_CONSUMER_KEY', NULL);
		$GLOBALS['CONSUMER_SECRET'] = env('YELP_CONSUMER_SECRET', NULL);
		$GLOBALS['TOKEN'] = env('YELP_TOKEN', NULL);
		$GLOBALS['TOKEN_SECRET'] = env('YELP_TOKEN_SECRET', NULL);
		$API_HOST = 'api.yelp.com';
		$SEARCH_PATH = '/v2/phone_search?category=food&phone=';

		$phone_number = $request->get('phone_number');
		if (strlen($phone_number) == 10) {
			$phone_number = "1" . $phone_number;
		}
		$phone_number = "+" . $phone_number;
		$SEARCH_PATH .= $phone_number;
		try {
			$response = json_decode(makeRequest($API_HOST, $SEARCH_PATH), true);
			$response['success'] = true;
		} catch (YelpOAuthException $e) {
			$response = array('success' => false, 'error' => 'Error while connecting to the Yelp API. (' . $e->getMessage() . ')');
		}
		$return_val = array();
		
		if ($response['total'] <= 0) {
			$return_val = array('success' => false, 'error' => 'Your restaurant\'s phone number is not listed on Yelp.');
		} else if ($response['success'] == false) {
			$return_val = $response;
		} else {
			$return_val = array('success' => true, 'listings' => $response['businesses']);
		}
		if ($return_val['success']) {
			return $this->response->array($return_val);
		} else {
			return $this->response->error($return_val['error'], 404);
		}
	}


}
