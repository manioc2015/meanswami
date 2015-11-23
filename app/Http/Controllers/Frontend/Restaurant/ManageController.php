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

	}

}
