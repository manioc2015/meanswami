<?php namespace App\Http\Controllers\Frontend\Restaurant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use App\Models\Client\Client;
use App\Models\Restaurant\Restaurant;
use App\Models\Restaurant\MenuItem;
use App\Models\Restaurant\MenuItemPrice;
use App\Models\Restaurant\MenuItemAttribute;
use App\Models\Restaurant\AdSlot;
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

	public function getIndex(Request $request) {
		return view('frontend.restaurant.manage');
	}

	public function getStats(Request $request) {
		$properties = Client::getProperties($this->client->id);
		$restaurants = array();
		$franchises = array();
		if (isset($properties['data'])) {
			$restaurants = $properties['data']['restaurants'];
			unset($properties['data']);
		}
		$franchises = $properties;
		$menu_items = MenuItem::getCountByClient($this->client->id);
		return $this->response->array(array('success' => true, 'restaurants' => $restaurants, 'franchises' => $franchises, 'menu_items' => $menu_items));	
	}

	public function getClientProperties(Request $request) {
		$properties = Client::getProperties($this->client->id);
		return $this->response->array(array('success' => true, 'data' => $properties));
	}

	public function getMenuItemsByProperty(Request $request) {
		$id = $request->input('id');
		$type = $request->input('property_type');
		$menu_items = MenuItem::getByProperty($id, $type, $this->client->id, false);
		return $this->response->array(array('success' => true, 'data' =>$menu_items));
	}

	public function postMenuItemSchedule(Request $request) {
		$menu_item_id = $request->input('menu_item_id');
		if (!$menu_item_id) {
			return $this->response->error('Missing menu_item_id.', 404);
		}
		$menuItemOwners = MenuItem::getOwnerClientIds($menu_item_id);
		if (!isset($menuItemOwners[$this->client->id])) {
			return $this->response->error('You do not have permissions to update this menu item.', 404);
		}
		$menuItem = MenuItem::find($menu_item_id);
		if ($menuItem) {
			$menuItem->availability = json_encode($request->input('availability'));
			$menuItem->save();
			return $this->response->array(array('success' => true));
		}
		$this->response->error('Menu item not found.', 404);
	}

	public function postMenuItem(Request $request) {
		$input = $request->input('menu_item');
		$id = isset_or($input['id']);
		if (isset($input['franchise_id']) || isset($input['restaurant_id'])) {
			if ($input['franchise_id'] && !$input['restaurant_id']) {
				$input['item']['property_id'] = $input['franchise_id'];
				$input['item']['property_type'] = 'Franchise';
			} else if ($input['restaurant_id']) {
				$input['item']['property_id'] = $input['restaurant_id'];
				$input['item']['property_type'] = 'Restaurant';
			}
		}
		$max_menu_items = 1;
		try {
			$max_menu_items = $input['item']['property_type'] == 'Franchise' ? Franchise::findOrFail($input['item']['property_id'])->max_menu_items : Restaurant::findOrFail($input['item']['property_id'])->max_menu_items;
		} catch (ModelNotFoundException $e) {
			return $this->response->error('Property not found.', 404);
		}
		$menu_item = null;
		$wasActive = false;
		if ($id) {
			$menu_item = MenuItem::find($id);
			$wasActive = $menu_item->active;
		}
		$curr_active_menu_items = MenuItem::getNumActive($input['item']['property_id'], $input['item']['property_type']);
		$input['item']['active'] = (($curr_active_menu_items < $max_menu_items) && isset_or($input['item']['active'], true)) || (($curr_active_menu_items >= $max_menu_items) && isset_or($input['item']['active'], true) && $wasActive);
		try {
			DB::transaction(function () use ($id, $input, &$menu_item) {
				$validPropertyClientsNew = $input['item']['property_type'] == 'Franchise' ? Franchise::getOwnerClientIds($input['item']['property_id']) : Restaurant::getOwnerClientIds($input['item']['property_id']);
				if (!$menu_item) {
					$input['item']['is_test_item'] = true;
					if (isset($validPropertyClientsNew[$this->client->id])) {
						$menu_item = MenuItem::create($input['item']);
					} else {
						throw new Exception('You do not have permissions to assign to this property.');
					}
				} else {
					$validClients = MenuItem::getOwnerClientIds($id);
					$validPropertyClientsOld = $menu_item->property_type == 'Franchise' ? Franchise::getOwnerClientIds($menu_item->property_id) : Restaurant::getOwnerClientIds($menu_item->property_id);
					if (isset($validClients[$this->client->id]) && isset($validPropertyClientsOld[$this->client->id]) && isset($validPropertyClientsNew[$this->client->id])) {
						$menu_item->update($input['item']);
					} else {
						throw new Exception('You do not have permissions to assign to this property.');
					}
				}
				$menu_item->save();
				$id = $menu_item->id;
				if (isset($input['prices'])) {
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
				}
				if (isset($input['cuisines'])) {
					MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 1)->forceDelete();
					foreach ($input['cuisines'] as $attribute_id) {
						$attribute = MenuItemAttribute::create(array('menu_item_id' => $id, 'attribute_group_id' => 1, 'attribute_id' => $attribute_id));
						$attribute->save();
					}
				}
				if (isset($input['diets'])) {
					MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 3)->forceDelete();
					foreach ($input['diets'] as $attribute_id) {
						$attribute = MenuItemAttribute::create(array('menu_item_id' => $id, 'attribute_group_id' => 3, 'attribute_id' => $attribute_id));
						$attribute->save();
					}
				}
				if (isset($input['allergens'])) {
					MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 4)->forceDelete();
					foreach ($input['allergens'] as $attribute_id) {
						$attribute = MenuItemAttribute::create(array('menu_item_id' => $id, 'attribute_group_id' => 4, 'attribute_id' => $attribute_id));
						$attribute->save();
					}
				}
				if (isset($input['organic'])) {
					MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 2)->forceDelete();
					$attribute = MenuItemAttribute::create(array('menu_item_id' => $id, 'attribute_group_id' => 2, 'attribute_id' => $input['organic']));
					$attribute->save();
				}
				if (isset($input['spicy'])) {
					MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 5)->forceDelete();
					$attribute = MenuItemAttribute::create(array('menu_item_id' => $id, 'attribute_group_id' => 5, 'attribute_id' => $input['spicy']));
					$attribute->save();
				}
			});
		} catch (Exception $e) {
			return $this->response->error($e->getMessage(), 404);
		}
		return $this->response->array(array('success' => true, 'menu_item_id' => $menu_item->id, 'inactive' => !$input['item']['active']));
	}

	public function getMenuItem(Request $request) {
		$id = $request->input('id');
		if ($id) {
			$menu_item = MenuItem::find($id);
			if ($menu_item) {
				$validClients = MenuItem::getOwnerClientIds($id);
				if (isset($validClients[$this->client->id])) {
					$ret = array('id' => $id);
					$ret['item']['name'] = $menu_item->name;
					$ret['item']['tagline'] = $menu_item->tagline;
					$ret['item']['main_ingredients'] = $menu_item->main_ingredients;
					if ($menu_item->property_type == 'Restaurant') {
						$ret['restaurant_id'] = $menu_item->property_id;
						$ret['franchise_id'] = null;
					} else {
						$ret['restaurant_id'] = null;
						$ret['franchise_id'] = $menu_item->property_id;
					}
					$prices = MenuItemPrice::where('menu_item_id', $id)->first();
					$ret['prices']['min_price'] = $prices['min_price'];
					$ret['prices']['max_price'] = $prices['max_price'];
					$ret['cuisines'] = [];
					$cuisines = MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 1)->get();
					foreach ($cuisines as $cuisine) {
						$ret['cuisines'][] = $cuisine->attribute_id;
					}
					$ret['diets'] = [];
					$diets = MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 3)->get();
					foreach ($diets as $diet) {
						$ret['diets'][] = $diet->attribute_id;
					}
					$ret['allergens'] = [];
					$allergens = MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 4)->get();
					foreach ($allergens as $allergen) {
						$ret['allergens'][] = $allergen->attribute_id;
					}
					$organic = MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 2)->first();
					$ret['organic'] = $organic->attribute_id;
					$spicy = MenuItemAttribute::where('menu_item_id', $id)->where('attribute_group_id', 5)->first();
					$ret['spicy'] = $spicy->attribute_id;
					return $this->response->array(array('success' => true, 'data' => $ret));
				}
			}
		}
		return $this->response->error('Unable to retrieve menu item.', 404);
	}

}
