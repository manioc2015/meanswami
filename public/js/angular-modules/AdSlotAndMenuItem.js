var openModal = function(modal, id) {
	var controllerElement = document.querySelector('#'+modal);
	var controllerScope = angular.element(controllerElement).scope();
	controllerScope.open(id);
}

'use strict';

var appModal = angular.module("AdSlotAndMenuItemModule", ["ngResource", "ui.bootstrap"])
  .factory("RestaurantDataResource", function ($resource)
  {
    var resource = $resource("/restaurant/:type/:operation",
      {
		type: '@type',
		operation: '@operation',
      }
    );

    resource.prototype.lookupMenuItem = function (id) {
      return resource.get(
        {
          type: 'menuitem',
          operation: 'lookup',
          id: id
        }).$promise;
    };

    resource.prototype.lookupAdSlot = function (id) {
      return resource.get(
        {
          type: 'adslot',
          operation: 'lookup',
          id: id
        }).$promise;
    };

    resource.prototype.lookupRestaurants = function () {
      return resource.get(
        {
          type: 'restaurants',
          operation: 'lookup'
        }).$promise;
    };

    resource.prototype.saveMenuItem = function (menuItem) {
      return resource.save(
        {
          type: 'menuitem',
          operation: 'save',
          menu_item: menuItem
        }).$promise;
    };

    resource.prototype.saveAdSlot = function (adSlot) {
      return resource.save(
        {
          type: 'adslot',
          operation: 'save',
          ad_slot: adSlot
        }).$promise;
    };

    return resource;
  })
  .controller("MenuItemInitCtrl", function($scope, $resource, RestaurantDataResource, $uibModal)
  {
    $scope.lookupComplete = false;
    $scope.error = false;
    $scope.menu_item = {};

    $scope.lookupMenuItem = function(id) {
      $scope.lookupComplete = false;
      $scope.error = false;
      var resource = new RestaurantDataResource();
      var promise = resource.lookupMenuItem(id);
      promise.then(function(data) {
        data = data.data;
        if (!data.id) {
          $scope.error = true;
        } else {
          $scope.error = false;
          $scope.menu_item = data;
        }
        $scope.lookupComplete = true;
      },
      function(response, status) {
          $scope.error = true;
          $scope.lookupComplete = true;
      });
    }

    $scope.open = function (id) {
      $scope.menu_item = {
      	id: null, 
      	prices: {
      		min_price: null,
      		max_price: null
      	}, 
      	item: {
      		name: '',
      		tagline: '',
      		main_ingredients: '',
      	}, 
      	cuisines: [], 
      	diets: [], 
      	allergens: [], 
      	organic: 1, 
      	spicy: 303
      };    	
	  if (id) {
	  	$scope.lookupMenuItem(id);
	  }
	  var modalInstance = $uibModal.open({
	    animation: true,
	    templateUrl: 'menuItemForm.html',
	    controller: 'MenuItemCtrl',
	    resolve: {
	      data: function () {
	        return $scope.menu_item;
	      }
	    },
	    size: '750'
	  });
    }
  })
  .controller("AdSlotInitCtrl", function($scope, $resource, RestaurantDataResource, $uibModal) {
    $scope.lookupComplete = false;
    $scope.error = false;
    $scope.ad_slot = {};

    $scope.lookupAdSlot = function(id) {
      $scope.lookupComplete = false;
      $scope.error = false;
      var resource = new RestaurantDataResource();
      var promise = resource.lookupAdSlot(id);
      promise.then(function(data) {
        data = data.data;
        if (!data.id) {
          $scope.error = true;
        } else {
          $scope.error = false;
          $scope.menu_item = data;
        }
        $scope.lookupComplete = true;
      },
      function(response, status) {
          $scope.error = true;
          $scope.lookupComplete = true;
      });
    }

    $scope.open = function (id) {
	  var modalInstance = $uibModal.open({
	    animation: true,
	    templateUrl: 'adSlotForm.html',
	    controller: 'AdSlotCtrl',
	    resolve: {
	      data: function () {
	        return $scope.ad_slot;
	      }
	    }
	  });

	  if (id) {
	  	$scope.lookupAdSlot(id);
	  }
    }
  }).controller('MenuItemCtrl', function ($scope, $uibModalInstance, RestaurantDataResource, data) {
  	  $scope.cuisines1 = [
  	  	{id: 200, value: 'American'},
		{id: 241, value: 'Argentinian'},
		{id: 243, value: 'Australian'},
		{id: 236, value: 'Baltic'},
		{id: 201, value: 'BBQ'},
		{id: 202, value: 'Belgian'},
		{id: 203, value: 'Brazilian'},
		{id: 204, value: 'Cajun'},
		{id: 205, value: 'Cambodian'},
		{id: 233, value: 'Cantonese'},
		{id: 206, value: 'Chinese'}
	  ];
	  $scope.cuisines2 = [
		{id: 207, value: 'Cuban'},
		{id: 208, value: 'English'},
		{id: 209, value: 'Filipino'},
		{id: 210, value: 'French'},
		{id: 211, value: 'German'},
		{id: 212, value: 'Greek'},
		{id: 235, value: 'Hunan'},
		{id: 213, value: 'Indian'},
		{id: 214, value: 'Irish'},
		{id: 215, value: 'Italian'},
		{id: 216, value: 'Japanese'}
	  ];
	  $scope.cuisines3 = [
		{id: 217, value: 'Korean'},
		{id: 218, value: 'Latin'},
		{id: 219, value: 'Lebanese'},
		{id: 220, value: 'Malaysian'},
		{id: 221, value: 'Mediterranean'},
		{id: 222, value: 'Mexican'},
		{id: 223, value: 'Middle Eastern'},
		{id: 224, value: 'Moroccan'},
		{id: 225, value: 'Nordic'},
		{id: 226, value: 'Pakistani'},
		{id: 227, value: 'Peruvian'}
	  ];
	  $scope.cuisines4 = [
		{id: 228, value: 'Polish'},
		{id: 229, value: 'Russian'},
		{id: 237, value: 'Scottish'},
		{id: 230, value: 'Southern / Soul'},
		{id: 231, value: 'Spanish'},
		{id: 234, value: 'Szechuan'},
		{id: 232, value: 'Taiwanese'},
		{id: 238, value: 'Thai'},
		{id: 239, value: 'Turkish'},
		{id: 240, value: 'Vietnamese'}
  	  ];

  	  $scope.organic = [{id: 1, value: 'None'},{id: 2, value: 'Some Ingredients'},{id: 3, value: 'Main Ingredients'},{id: 4, value: 'All Ingredients'}];

  	  $scope.allergens = [
		{id: 11, value: 'Corn'},
		{id: 12, value: 'Dairy'},
		{id: 13, value: 'Eggs'},
		{id: 14, value: 'Fish'},
		{id: 15, value: 'Gelatin'},
		{id: 16, value: 'Gluten'},
		{id: 17, value: 'Meats'},
		{id: 18, value: 'MSG'},
		{id: 19, value: 'Peanuts'},
		{id: 20, value: 'Seeds'},
		{id: 21, value: 'Shellfish'},
		{id: 22, value: 'Soy'},
		{id: 24, value: 'Spices'},
		{id: 25, value: 'Tree Nuts'},
		{id: 26, value: 'Wheat'}
  	  ];

  	  $scope.diets = [
		{id: 101, value: 'Low-Calorie'},
		{id: 103, value: 'Low-Carb'},
		{id: 103, value: 'Low-Cholesterol'},
		{id: 104, value: 'Low-Fat'},
		{id: 105, value: 'Low-Glycemic'},
		{id: 106, value: 'Low-Protein'},
		{id: 107, value: 'Low-Sodium'},
		{id: 108, value: 'Halal'},
		{id: 109, value: 'Ital'},
		{id: 110, value: 'Kosher'},
		{id: 111, value: 'Vegan'},
		{id: 112, value: 'Vegeterian'}
  	  ];

  	  $scope.spicy = [{id: 302, value: 'Yes'}, {id: 303, value: 'No'}, {id: 304, value: 'Optional'}];

	  $scope.menu_item = data;
	  $scope.step = 1;
	  $scope.menuItemAdded = false;

	  $scope.restaurants = {};

	  $scope.doNext = function() {
	    $scope.step++;
	    if ($scope.step == 4) {
		    var resource = new RestaurantDataResource();
		    var promise = null;
	  		promise = resource.lookupRestaurants();
	        promise.then(function(data) {
	        	if (data['data']) {
		        	$scope.restaurants = data['data']['restaurants'];
			        if (!$scope.menu_item.franchise_id) {
				        $scope.menu_item.franchise_id = $scope.restaurants[0].franchise_id;
				    }
			        if (!$scope.menu_item.restaurant_id) {
				        $scope.menu_item.restaurant_id = $scope.restaurants[0].id;
				    }
		        } else {
		        	$scope.restaurants = data;
			        if (!$scope.menu_item.franchise_id) {
				        $scope.menu_item.franchise_id = $scope.restaurants[0].franchise_id;
				    }
			        if (!$scope.menu_item.restaurant_id) {
				        $scope.menu_item.restaurant_id = $scope.restaurants[0]['restaurants'][0]['id'];
				    }
		        }
	    	},
	        function(response, status) {
	          alert('Something went wrong... please try again.');
	        });
	    }
	  }

	  $scope.doBack = function() {
	  	$scope.step--;
	  }

	  $scope.franchiseSelected = function(id) {
	  	if ($scope.menu_item.franchise_id == id && $scope.menu_item.restaurant_id == null) {
	  		return true;
	  	}
	  	return false;
	  }

	  $scope.selectFranchise = function(id) {
	  	$scope.menu_item.franchise_id = id;
	  	$scope.menu_item.restaurant_id = null;
	  }

	  $scope.restaurantSelected = function(franchiseId, restaurantId) {
	  	if ($scope.menu_item.franchise_id == franchiseId && $scope.menu_item.restaurant_id == restaurantId) {
	  		return true;
	  	}
	  	return false;
	  }

	  $scope.selectRestaurant = function(franchiseId, restaurantId) {
	  	$scope.menu_item.franchise_id = franchiseId;
	  	$scope.menu_item.restaurant_id = restaurantId;
	  }

	  $scope.cuisineSelected = function(id) {
	  	if ($scope.menu_item.cuisines.indexOf(id) == -1) {
	  	  return false;
	  	}
	  	return true;
	  }

	  $scope.selectCuisine = function(id, event) {
	  	var checked = event.target.checked;
	  	if (!checked) {
	  		var pos = $scope.menu_item.cuisines.indexOf(id);
	  		$scope.menu_item.cuisines.splice(pos, 1);
	  		return true;
	  	} else {
		  	if ($scope.menu_item.cuisines.length >=3) {
		  	  event.preventDefault();
		  	  return false;
		  	}
		  	$scope.menu_item.cuisines.push(id);
		  	return true;
		}
	  }

	  $scope.allergenSelected = function(id) {
	  	if ($scope.menu_item.allergens.indexOf(id) == -1) {
	  	  return false;
	  	}
	  	return true;
	  }

	  $scope.selectAllergen = function(id, event) {
	  	var checked = event.target.checked;
	  	if (!checked) {
	  		var pos = $scope.menu_item.allergens.indexOf(id);
	  		$scope.menu_item.allergens.splice(pos, 1);
	  		return true;
	  	} else {
		  	$scope.menu_item.allergens.push(id);
		  	return true;
		}
	  }

	  $scope.dietSelected = function(id) {
	  	if ($scope.menu_item.diets.indexOf(id) == -1) {
	  	  return false;
	  	}
	  	return true;
	  }

	  $scope.selectDiet = function(id, event) {
	  	var checked = event.target.checked;
	  	if (!checked) {
	  		var pos = $scope.menu_item.diets.indexOf(id);
	  		$scope.menu_item.diets.splice(pos, 1);
	  		return true;
	  	} else {
		  	$scope.menu_item.diets.push(id);
		  	return true;
		}
	  }

	  $scope.organicSelected = function(id) {
	  	if ($scope.menu_item.organic == id) {
	  	  return true;
	  	}
	  	return false;
	  }

	  $scope.selectOrganic = function(id) {
	  	$scope.menu_item.organic = id;
	  }

	  $scope.spicySelected = function(id) {
	  	if ($scope.menu_item.spicy == id) {
	  	  return true;
	  	}
	  	return false;
	  }

	  $scope.selectSpicy = function(id) {
	  	$scope.menu_item.spicy = id;
	  }

	  $scope.create = function () {
	    var resource = new RestaurantDataResource();
	    var promise = null;
  		promise = resource.saveMenuItem($scope.menu_item);
        promise.then(function(data) {
        	if (data['success']) {
        		$scope.menuItemAdded = true;
	    	}
    	},
        function(response, status) {
          alert('Something went wrong... please try again.');
        });
	  };

	  $scope.cancel = function () {
	    $uibModalInstance.dismiss('cancel');
	  };
}).controller('AdSlotCtrl', function ($scope, $uibModalInstance, RestaurantDataResource, data) {
	  $scope.ad_slot = data;
	  $scope.step = 1;
	  $scope.doNext = function() {

	  }

	  $scope.create = function () {
	    var resource = new RestaurantDataResource();
	    var promise = null;
  		promise = resource.saveAdSlot($scope.ad_slot);
        promise.then(function(data) {
        	if (data['success']) {
	    		$uibModalInstance.close();
	    	}
    	},
        function(response, status) {
          alert('Something went wrong... please try again.');
        });
	  };

	  $scope.cancel = function () {
	    $uibModalInstance.dismiss('cancel');
	  };
});