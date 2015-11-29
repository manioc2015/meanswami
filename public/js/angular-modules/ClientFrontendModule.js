var openModal = function(modal, id) {
	var controllerElement = document.querySelector('#'+modal);
	var controllerScope = angular.element(controllerElement).scope();
	controllerScope.open(id);
}

'use strict';

var app = angular.module("ClientFrontendModule", ["ngResource", "ui.bootstrap", "angularSpinners"])
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

    resource.prototype.lookupPropertyMenuItems = function (id, property_type) {
      return resource.get(
        {
          type: 'menuitem',
          operation: 'lookupByProperty',
          id: id,
          property_type: property_type
        }).$promise;
    };

    resource.prototype.lookupProperties = function () {
      return resource.get(
        {
          type: 'properties',
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

    resource.prototype.scheduleMenuItem = function(id, availability) {
    	return resource.save(
    	  {
    	  	type: 'menuitem',
    	  	operation: 'schedule',
    	  	menu_item_id: id,
    	  	availability: availability
    	  }).$promise;
    };

    return resource;
  })
  .factory("RestaurantFranchiseManageResource", function ($resource)
  {
    var resource = $resource("/restaurant/:type/:operation",
      {
        type: '@type',
        operation: '@operation'
      }
    );

    resource.prototype.getStats = function () {
      return resource.get(
        {
          type: 'manage',
          operation: 'stats'
        }).$promise;
    };

    resource.prototype.lookupPropertyMenuItems = function (id, property_type) {
      return resource.get(
        {
          type: 'menuitem',
          operation: 'lookupByProperty',
          id: id,
          property_type: property_type
        }).$promise;
    };

    resource.prototype.updateMenuItemActive = function (id, value, restaurant_id) {
      return resource.save(
      {
        type: 'menuitem',
        operation: 'save', 
        menu_item: {id: id, item: {active: value, property_id: restaurant_id, property_type: 'Restaurant'}}
      }).$promise;
    }

    resource.prototype.lookupRestaurant = function(id) {
      return resource.get(
        {
          type: 'manage',
          operation: 'restaurant',
          id: id
        }).$promise;
    };

    resource.prototype.lookupFranchise = function(id) {
      return resource.get(
        {
          type: 'manage',
          operation: 'franchise',
          id: id
        }).$promise;
    };

    return resource;
})
.service("MenuItemService", [function() {
    this.menu_item = {menu_item_id: null, opened: false};
    this.openModal = function() {
      this.menu_item.opened = true;
    }
    this.getOpened = function() {
      return this.menu_item.opened;
    }
    this.setMenuItemId = function(menu_item_id) {
      this.menu_item.opened = false;
      this.menu_item.menu_item_id = menu_item_id;
    }
    this.getMenuItemId = function() {
      return this.menu_item.menu_item_id;
    }
    return this;
}])
  .controller("MenuItemInitCtrl", ['$scope', '$resource', 'RestaurantDataResource', '$uibModal', 'MenuItemService', function($scope, $resource, RestaurantDataResource, $uibModal, MenuItemService)
  {
    $scope.lookupComplete = false;
    $scope.menu_item = {};
    $scope.id = null;

    $scope.lookupMenuItem = function(id) {
      $scope.lookupComplete = false;
      var resource = new RestaurantDataResource();
      var promise = resource.lookupMenuItem(id);
      promise.then(function(data) {
      	if (data['success']) {
	        data = data.data;
	        if (data.id) {
	          $scope.id = data.id;
	          $scope.menu_item = data;
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
	        $scope.lookupComplete = true;
	    }
      },
      function(response, status) {
          alert('Something went wrong... please try again.');
      });
    }

    $scope.open = function (id) {
      $scope.id = id;
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
      	spicy: 303,
      	availability: []
      };    	
	  if (id) {
	  	$scope.lookupMenuItem(id);
	  } else {
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
    }
  }]).controller('MenuItemCtrl', ['$scope', '$uibModalInstance', 'RestaurantDataResource', 'data', function ($scope, $uibModalInstance, RestaurantDataResource, data) {
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

  	  $scope.id = data.id;
	  $scope.menu_item = data;
	  $scope.step = 1;
	  $scope.menuItemAdded = false;

	  $scope.restaurants = {};

	  $scope.time_slots = [];
	  var tod = 'AM';
	  var time = null;
	  var h = null;
	  for (h = 6; h <= 11; h++) {
	  	id = h + ':00';
	  	time = h + ':00' + tod;
	  	$scope.time_slots.push({id: id, value: time});
	  	id = h + ':30';
	  	time = h + ':30' + tod;
	  	$scope.time_slots.push({id: id, value: time});
	  }
	  tod = 'PM';
	  h = 12;
	  id = h + ':00';
	  time = h + ':00' + tod;
	  $scope.time_slots.push({id: id, value: time});
	  id = h + ':30';
	  time = h + ':30' + tod;
	  $scope.time_slots.push({id: id, value: time});
	  for (h = 1; h <= 11; h++) {
	  	id = (12 + h) + ':00';
	  	time = h + ':00' + tod;
	  	$scope.time_slots.push({id: time, value: time});
	  	id = (12 + h) + ':30';
	  	time = h + ':30' + tod;
	  	$scope.time_slots.push({id: time, value: time});
	  }
	  tod = 'AM';
	  h = 12;
	  id = '0' + ':00';
	  time = h + ':00' + tod;
	  $scope.time_slots.push({id: time, value: time});
	  id = '0' + ':30';
	  time = h + ':30' + tod;
	  $scope.time_slots.push({id: time, value: time});
	  for (h = 1; h <= 5; h++) {
	  	id = h + ':00';
	  	time = h + ':00' + tod;
	  	$scope.time_slots.push({id: time, value: time});
	  	id = h + ':30';
	  	time = h + ':30' + tod;
	  	$scope.time_slots.push({id: time, value: time});
	  }

	  $scope.new_slot = {days: ['1','2','3','4','5','6','7'], times: []};

	  $scope.days_map = {'1': 'Mon', '2': 'Tue', '3': 'Wed', '4': 'Thu', '5': 'Fri', '6': 'Sat', '7': 'Sun'};

	  $scope.start_time = null;
	  $scope.end_time = null;
	  $scope.day_error = false;
	  $scope.time_error = false;
	  $scope.print_availability = function(availability) {

	  }

	  $scope.add_time = function() {
	  	if (!$scope.start_time || !$scope.end_time) {
	  		return false;
	  	}
	  	var new_time = {start_time: $scope.start_time, end_time: $scope.end_time};
	  	$scope.new_slot.times.push(new_time);
	  	$scope.start_time = null;
	  	$scope.end_time = null;
	  }

	  $scope.add_slot = function() {
	  	$scope.day_error = false;
	  	$scope.time_error = false;
	  	if ($scope.new_slot.days.length == 0) {
	  		$scope.day_error = true;
	  	}
	  	if ($scope.new_slot.times.length == 0) {
	  		$scope.time_error = true;
	  	}
	  	if ($scope.day_error || $scope.time_error) {
	  		return false;
	  	}
	  	var new_slot = $scope.new_slot;
	  	$scope.menu_item.availability.push(new_slot);
	  	$scope.new_slot = {days: ['1','2','3','4','5','6','7'], times: []};
	  	$scope.day_error = false;
	  	$scope.time_error = false;
	  }

	  $scope.remove_time = function(index) {
	  	$scope.new_slot.times.splice(index, 1);
	  }

	  $scope.remove_slot = function(index) {
	  	$scope.menu_item.availability.splice(index, 1);
	  }

	  $scope.daySelected = function(day, index) {
	  	if (index == null) {
	  		return $scope.new_slot.days.indexOf(day) >= 0;
	  	}
	  	return $scope.menu_item.availability[index]['days'].indexOf(day) >= 0;
	  }

	  $scope.selectDay = function(event, day, index) {
	  	var checked = event.target.checked;
	  	if (!checked) {
	  		if (index == null) {
		  		var pos = $scope.new_slot.days.indexOf(day);
				$scope.new_slot.days.splice(pos, 1);
	  		} else {
	  			var pos = $scope.menu_item.availability[index].days.indexOf(day);
	  			$scope.menu_item.availability[index].days.splice(pos, 1);
	  		}
	  	} else {
	  		if (index == null) {
			  $scope.new_slot.days.push(day);
			} else {
			  $scope.menu_item.availability[index].days.push(day);
			}
		}
		return true;
	  }

	  $scope.doNext = function() {
	    if ($scope.step == 3) {
		    var resource = new RestaurantDataResource();
		    var promise = null;
	  		promise = resource.lookupProperties();
	        promise.then(function(data) {
	        	if (data['success']) {
	        		data = data['data'];
	        	}
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
	    $scope.step++;
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
	  	} else {
		  	$scope.menu_item.allergens.push(id);
		}
		return true;
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
	  	} else {
		  	$scope.menu_item.diets.push(id);
		}
		return true;
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
        		$scope.menu_item.id = data['menu_item_id'];
        		if (data['inactive']) {
        			$scope.menuItemInactive = true;
        		}
	    	}
    	},
        function(response, status) {
          alert('Something went wrong... please try again.');
        });
	  };

	  $scope.schedule = function () {
	  	$scope.doSchedule = true;
	  }

	  $scope.finalize = function () {
	    var resource = new RestaurantDataResource();
	    var promise = null;
  		promise = resource.scheduleMenuItem($scope.menu_item.id, $scope.menu_item.availability);
        promise.then(function(data) {
        	$scope.scheduled = true;
    	},
        function(response, status) {
          alert('Something went wrong... please try again.');
        });	
	  }

	  $scope.cancel = function () {
	    $uibModalInstance.dismiss('cancel');
	  };
}]).controller("RestaurantFranchiseManageController", ['$scope', '$resource', 'RestaurantFranchiseManageResource', function($scope, $resource, RestaurantFranchiseManageResource)
  {
    $scope.landing = true;
    $scope.menuItems = false;
    $scope.currentPage = 0;
    $scope.pageSize = 50;
    $scope.menu_items = {};

    $scope.numberOfPages=function(){
        return Math.ceil($scope.restaurants.length/$scope.pageSize);                
    }
    $scope.lookupComplete = false;
    $scope.list = function () {
      var resource = new RestaurantFranchiseManageResource();
      var promise = null;
      promise = resource.getStats();
      promise.then(function(data) {
        if (data['success']) {
          $scope.restaurants = data['restaurants'];
          $scope.franchises = data['franchises'];
          $scope.menu_item_count = data['menu_items'];
          $scope.lookupComplete = true;
      }
      },
      function(response, status) {
        alert('Something went wrong... please try again.');
      });
    };

    $scope.showMenuItems = function (restaurant_id, restaurant_name, max_menu_items) {
        $scope.$broadcast("loadMenuItemsModal", {restaurant_id: restaurant_id, restaurant_name: restaurant_name, max_menu_items: max_menu_items});
    };

    $scope.list();
}]).controller("MenuItemsInitCtrl", ['$scope', '$resource', 'RestaurantFranchiseManageResource', '$uibModal', function($scope, $resource, RestaurantFranchiseManageResource, $uibModal)
  {
    $scope.menu_items = {};

    $scope.$on("loadMenuItemsModal", function (event, args) {
      var resource = new RestaurantFranchiseManageResource();
      var promise = null;
      promise = resource.lookupPropertyMenuItems(args.restaurant_id, 'Restaurant');
      promise.then(function(data) {
        if (data['success']) {
          var modalInstance = $uibModal.open({
            animation: true,
            templateUrl: 'menuItems.html',
            controller: 'MenuItemsCtrl',
            resolve: {
              data: function () {
                return data['data'];
              },
              restaurant_id: function () {
                return args.restaurant_id;
              },
              restaurant_name: function () {
                return args.restaurant_name;
              },
              max_menu_items: function () {
                return args.max_menu_items;
              }
            },
            size: '750'
          });
        }
      },
      function(response, status) {
        alert('Something went wrong... please try again.');
      });
    });
}]).controller("MenuItemsCtrl", ['$scope', '$uibModalInstance', 'RestaurantFranchiseManageResource', 'spinnerService', 'MenuItemService', 'data', 'restaurant_id', 'restaurant_name', 'max_menu_items', function ($scope, $uibModalInstance, RestaurantFranchiseManageResource, spinnerService, MenuItemService, data, restaurant_id, restaurant_name, max_menu_items)
{
    $scope.lookupComplete = false;
    $scope.menu_items = data;
    $scope.restaurant_id = restaurant_id;
    $scope.restaurant_name = restaurant_name;
    $scope.num_active = 0;
    $scope.max_menu_items = max_menu_items;
    for (var m = 0; m < $scope.menu_items.length; m++) {
      if ($scope.menu_items[m]['active']) {
        $scope.num_active++;
      }
    }
    $scope.lookupComplete = true;

    $scope.cancel = function () {
      $uibModalInstance.dismiss('cancel');
    };

    $scope.updateActive = function (index, val, event) {
      spinnerService.show('spinner');
      $scope.menu_items[index].active = val;
      var resource = new RestaurantFranchiseManageResource();
      var promise = null;
      promise = resource.updateMenuItemActive($scope.menu_items[index].id, val, $scope.restaurant_id);
      promise.then(function(data) {
        if (data['success']) {
          if (val && data['inactive']) {
            $scope.menu_items[index].active = false;
          } else {
            $scope.num_active += (val && !data['inactive'] ? 1 : -1);            
          }
          spinnerService.hide('spinner');
        }
      },
      function(response, status) {
        spinnerService.hide('spinner');
        alert('Something went wrong... please try again.');
      });
    };

    $scope.openMenuItem = function (id) {
    	$scope.cancel();
    	openModal('menuItemModal', id);
    }
}]);

app.filter('startFrom', function() {
    return function(input, start) {
        start = +start; //parse to int
        return input.slice(start);
    }
});