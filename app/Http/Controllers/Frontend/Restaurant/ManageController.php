<?php namespace App\Http\Controllers\Frontend\Restaurant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use App\Models\Client\Client;
use App\Models\Restaurant\Restaurant;
use App\Models\Restaurant\MenuItem;
use App\Models\Restaurant\MenuItemPrice;
use App\Models\Restaurant\MenuItemAttribute;
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
		$user = auth()->user();
		if ($user) {
			$this->client = Client::where('user_id', $user->id)->first();
		}
	}

	public function getIndex() {

	}

	public function getRestaurants(Request $request) {
		if ($this->client) {
			$properties = Client::getProperties($this->client->id);
			return $this->response->array($properties);
		}
		return $this->response->error('', 404);
	}

	public function postMenuItem(Request $request) {
		$input = $request->input('menu_item');
		$id = $input['id'];
		if ($input['franchise_id'] && !$input['restaurant_id']) {
			$input['item']['property_id'] = $input['franchise_id'];
			$input['item']['property_type'] = 'Franchise';
		} else if ($input['restaurant_id']) {
			$input['item']['property_id'] = $input['restaurant_id'];
			$input['item']['property_type'] = 'Restaurant';
		}
		try {
			DB::transaction(function () use ($id, $input) {
				$menu_item = null;
				if ($id) {
					$menu_item = MenuItem::find($id);
				}
				if (!$menu_item) {
					$input['item']['is_test_item'] = true;
					$input['item']['active'] = true;
					$menu_item = MenuItem::create($input['item']);
				} else {
					$validClients = MenuItem::getOwnerClientIds($id);
					if (isset($validClients[$this->client->id])) {
						$menu_item->update($input['item']);
					} else {
						throw new Exception('You do not have permissions to update this menu item.');
					}
				}
				$menu_item->save();
				$id = $menu_item->id;
				$input['prices']['menu_item_id'] = $id;
				if (!trim($input['prices']['min_price'])) {
					$input['prices']['min_price'] = null;
				}
				if (!trim($input['prices']['max_price'])) {
					$input['prices']['max_price'] = null;
				}
				$menu_item_price = MenuItemPrice::where('menu_item_id', $id)->first();
				if ($menu_item_price) {
					$menu_item_price->update($input['prices']);
				} else {
					$menu_item_price = MenuItemPrice::create($input['prices']);
				}
				$menu_item_price->save();
				MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 1)->forceDelete();
				foreach ($input['cuisines'] as $attribute_id) {
					$attribute = MenuItemAttribute::create(array('menu_item_id' => $id, 'attribute_group_id' => 1, 'attribute_id' => $attribute_id));
					$attribute->save();
				}
				MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 3)->forceDelete();
				foreach ($input['diets'] as $attribute_id) {
					$attribute = MenuItemAttribute::create(array('menu_item_id' => $id, 'attribute_group_id' => 3, 'attribute_id' => $attribute_id));
					$attribute->save();
				}
				MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 4)->forceDelete();
				foreach ($input['allergens'] as $attribute_id) {
					$attribute = MenuItemAttribute::create(array('menu_item_id' => $id, 'attribute_group_id' => 4, 'attribute_id' => $attribute_id));
					$attribute->save();
				}
				MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 2)->forceDelete();
				$attribute = MenuItemAttribute::create(array('menu_item_id' => $id, 'attribute_group_id' => 2, 'attribute_id' => $input['organic']));
				$attribute->save();
				MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 5)->forceDelete();
				$attribute = MenuItemAttribute::create(array('menu_item_id' => $id, 'attribute_group_id' => 5, 'attribute_id' => $input['spicy']));
				$attribute->save();
			});
		} catch (Exception $e) {
			return $this->response->error('', 404);
		}
		return $this->response->array(array('success' => true));
	}

}
