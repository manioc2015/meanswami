var openModal = function(modal, id, schedule) {
	var controllerElement = document.querySelector('#'+modal);
	var controllerScope = angular.element(controllerElement).scope();
	controllerScope.open(id, schedule);
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
.factory("RestaurantSignupResource", function ($resource)
{
var resource = $resource("/restaurant/signup/:operation",
  {
    operation: '@operation'
  }
);

resource.prototype.lookupPhoneNumber = function () {
  return resource.save(
    {
      operation: 'lookup',
      phone_number: this.phoneNumber
    }).$promise;
};

resource.prototype.addRestaurant = function (restaurant) {
  return resource.save(
    {
      operation: 'addRestaurant',
      restaurant_data: restaurant
    }).$promise;
};

resource.prototype.addClient = function (client) {
  return resource.save(
    {
      operation: 'addClient',
      client_data: client
    }).$promise;
};

return resource;
})
.factory("MenuItemCountService", [function() {
    this.menu_item_count = {};
    this.loaded = true;
    this.mass_set_counts = function(counts) {
      this.menu_item_count = counts;
    }
    this.set_single_active = function(restaurant_id, active_count) {
    	this.menu_item_count['Restaurant'][restaurant_id]['active'] = active_count;
    }
    this.set_single_total = function(restaurant_id, total_count) {
    	this.menu_item_count['Restaurant'][restaurant_id]['total'] = total_count;
    }
    return this;
}])
  .controller("RestaurantSignupController", function($scope, $resource, RestaurantSignupResource)
  {
    $scope.lookupComplete = false;
    $scope.hasNoResults = true;
    $scope.selectedRestaurantIndex = 0;
    $scope.showRestaurantFormStatus = false;
    $scope.showClientFormStatus = false;
    $scope.promptedToRegister = false;
    $scope.allResultsSignedUp = false;

    $scope.lookupRestaurant = function() {
      $scope.lookupComplete = false;
      $scope.hasNoResults = true;
      $scope.selectedRestaurantIndex = 0;
      $scope.showRestaurantFormStatus = false;
      $scope.showClientFormStatus = false;
      $scope.promptedToRegister = false;
      $scope.allResultsSignedUp = false;
      var resource = new RestaurantSignupResource();
      resource.phoneNumber = $scope.phoneNumber;
      var promise = resource.lookupPhoneNumber();
      promise.then(function(data) {
        if (data['success']) {
          data = data.data;
        }
        if (data.length == 0) {
          $scope.hasNoResults = true;
        } else {
          $scope.hasNoResults = false;
          $scope.restaurants = data;
          var allResultsSignedUp = true;
          for (var i in data) {
            if (!data[i]['signed_up']) {
              $scope.selectedRestaurantIndex = i;
              allResultsSignedUp = false;
              break;
            }
          }
          $scope.allResultsSignedUp = allResultsSignedUp;
        }
        $scope.lookupComplete = true;
      },
      function(response, status) {
          $scope.hasNoResults = true;
          $scope.lookupComplete = true;
      });
    }

    $scope.showRestaurantForm = function() {
      $scope.showRestaurantFormStatus = false;
      $scope.showClientFormStatus = false;
      $scope.promptedToRegister = false;
      if (parseInt($scope.selectedRestaurantIndex) >= 0 ) {
        var index = $scope.selectedRestaurantIndex;
        this.restaurant = {};
        this.restaurant.name = this.restaurants[index].name;
        this.restaurant.sp_listing_id = this.restaurants[index].sp_listing_id;
        this.restaurant.yelp_listing_id = this.restaurants[index].yelp_listing_id;
        this.restaurant.is_claimed_on_yelp = this.restaurants[index].is_claimed_on_yelp;
        this.restaurant.address1 = this.restaurants[index].address1;
        this.restaurant.address2 = this.restaurants[index].address2;
        this.restaurant.cross_streets = this.restaurants[index].cross_streets;
        this.restaurant.city = this.restaurants[index].city;
        this.restaurant.state = this.restaurants[index].state;
        this.restaurant.zipcode = this.restaurants[index].zipcode;
        this.restaurant.country = this.restaurants[index].country;
        this.restaurant.phone = this.restaurants[index].phone;
        this.restaurant.lat = this.restaurants[index].lat;
        this.restaurant.lon = this.restaurants[index].lon;
        this.restaurant.website = '';
        this.restaurant.description = '';
        $scope.showRestaurantFormStatus = true;
      }
    }

    $scope.showClientForm = function() {
      $scope.showClientFormStatus = false;
      $scope.promptedToRegister = false;
      this.client = {};
      this.client.client_name = '';
      this.client.business_name = this.restaurant.name;
      this.client.address1 = this.restaurant.address1;
      this.client.address2 = this.restaurant.address2;
      this.client.city = this.restaurant.city;
      this.client.state = this.restaurant.state;
      this.client.zipcode = this.restaurant.zipcode;
      this.client.country = this.restaurant.country;
      this.client.phone1 = this.restaurant.phone;
      this.client.phone2 = '';
      $scope.showRestaurantFormStatus = false;
      $scope.showClientFormStatus = true;
    }

    $scope.selectRestaurant = function(index) {
      $scope.selectedRestaurantIndex = index;
    }

    $scope.doNext = function() {
      if (!$scope.showRestaurantFormStatus && !$scope.showClientFormStatus) {
        $scope.showRestaurantForm();
      } else if (!$scope.showClientFormStatus) {
        var resource = new RestaurantSignupResource();
        var promise = resource.addRestaurant(this.restaurant);
        promise.then(function(data) {
          if (data['action'] == 'newClient') {
            $scope.showClientForm();
          } else if (data['action'] == 'redirect') {
            window.location.href = data['url'];
          }
        },
        function(response, status) {
          alert('Something went wrong... please try again.');
        });
      } else if (!$scope.promptedToRegister) {
        var resource = new RestaurantSignupResource();
        var promise = resource.addClient(this.client);
        promise.then(function(data) {
          if (data['action'] == 'redirect') {
            window.location.href = data['url'];
          }
        },
        function(response, status) {
          alert('Something went wrong... please try again.');
        });
      }
    }
  })
  .controller("MenuItemInitCtrl", ['$scope', '$resource', 'RestaurantDataResource', '$uibModal', function($scope, $resource, RestaurantDataResource, $uibModal)
  {
    $scope.lookupComplete = false;
    $scope.menu_item = {};
    $scope.id = null;

    $scope.lookupMenuItem = function(id, schedule) {
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
				    },
					loadSchedule: function() {
						return schedule;
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

    $scope.open = function (id, schedule) {
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
      	allergens: [11,12,13,14,15,16,17,18,19,20,21,22,24,25,26], 
      	organic: 1, 
      	spicy: 303,
      	availability: {'days': [1,2,3,4,5,6,7], 'courses': ['breakfast', 'brunch', 'lunch', 'tea', 'dinner', 'late-night']}
      };    	
	  if (id) {
	  	$scope.lookupMenuItem(id, schedule);
	  } else {
	  	var modalInstance = $uibModal.open({
		    animation: true,
		    templateUrl: 'menuItemForm.html',
		    controller: 'MenuItemCtrl',
		    resolve: {
		      data: function () {
		        return $scope.menu_item;
		      },
		      loadSchedule: function() {
		      	return schedule;
		      }
		    },
		    size: '750'
		  });
	  }
    }
  }]).controller('MenuItemCtrl', ['$scope', '$uibModalInstance', 'RestaurantDataResource', 'MenuItemCountService', 'data', 'loadSchedule', function ($scope, $uibModalInstance, RestaurantDataResource, MenuItemCountService, data, loadSchedule) {
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

	  $scope.day_error = false;

	  if (loadSchedule) {
	  	$scope.menuItemAdded = true;
	  	$scope.step = 5;
	  	$scope.doSchedule = true;
	  }

	  $scope.daySelected = function(day) {
	  	return $scope.menu_item.availability['days'].indexOf(day) >= 0;
	  }

	  $scope.selectDay = function(event, day) {
	  	var checked = event.target.checked;
	  	if (!checked) {
  			var pos = $scope.menu_item.availability.days.indexOf(day);
  			$scope.menu_item.availability.days.splice(pos, 1);
	  	} else {
		  	$scope.menu_item.availability.days.push(day);
		}
		return true;
	  }

	  $scope.courseSelected = function(course) {
	  	return $scope.menu_item.availability['courses'].indexOf(course) >= 0;
	  }

	  $scope.selectCourse = function(event, course) {
	  	var checked = event.target.checked;
	  	if (!checked) {
  			var pos = $scope.menu_item.availability.courses.indexOf(course);
  			$scope.menu_item.availability.courses.splice(pos, 1);
	  	} else {
		  	$scope.menu_item.availability.courses.push(course);
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
        		if (MenuItemCountService.loaded && !$scope.menu_item.id) {
		            MenuItemCountService.set_single_total($scope.menu_item.restaurant_id, MenuItemCountService.menu_item_count['Restaurant'][$scope.menu_item.restaurant_id]['total'] + 1);
		        }
        		if (data['inactive']) {
        			$scope.menuItemInactive = true;
        		} else {
	        		if (MenuItemCountService.loaded && !$scope.menu_item.id) {
		            MenuItemCountService.set_single_active($scope.menu_item.restaurant_id, MenuItemCountService.menu_item_count['Restaurant'][$scope.menu_item.restaurant_id]['active'] + 1);
    	    		}
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
}]).controller("RestaurantFranchiseManageController", ['$scope', '$resource', 'RestaurantFranchiseManageResource', 'MenuItemCountService', function($scope, $resource, RestaurantFranchiseManageResource, MenuItemCountService)
  {
    $scope.landing = true;
    $scope.menuItems = false;
    $scope.currentPage = 1;
    $scope.totalItems = 0;
    $scope.itemsPerPage = 25;
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
          $scope.totalItems = data['restaurants'].length;
          $scope.franchises = data['franchises'];
          $scope.menu_item_count = data['menu_items'];
          MenuItemCountService.mass_set_counts($scope.menu_item_count);
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
            size: '1050'
          });
        }
      },
      function(response, status) {
        alert('Something went wrong... please try again.');
      });
    });
}]).controller("MenuItemsCtrl", ['$scope', '$uibModalInstance', 'RestaurantFranchiseManageResource', 'spinnerService', 'MenuItemCountService', 'data', 'restaurant_id', 'restaurant_name', 'max_menu_items', function ($scope, $uibModalInstance, RestaurantFranchiseManageResource, spinnerService, MenuItemCountService, data, restaurant_id, restaurant_name, max_menu_items)
{
    $scope.lookupComplete = false;
    $scope.menu_items = data;
    $scope.restaurant_id = restaurant_id;
    $scope.restaurant_name = restaurant_name;
    $scope.num_active = 0;
    $scope.currentPage = 1;
    $scope.totalItems = 0;
    $scope.itemsPerPage = 10;
  	$scope.totalItems = $scope.menu_items.length;

	$scope.days_map = {'1': 'Mon', '2': 'Tue', '3': 'Wed', '4': 'Thu', '5': 'Fri', '6': 'Sat', '7': 'Sun'};
	$scope.courses_map = {'breakfast': 'Breakfast', 'brunch': 'Brunch', 'lunch': 'Lunch', 'tea': 'Tea', 'dinner': 'Dinner', 'late-night': 'Late-Night'};

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
            MenuItemCountService.set_single_active($scope.restaurant_id, $scope.num_active);
          }
          spinnerService.hide('spinner');
        }
      },
      function(response, status) {
        spinnerService.hide('spinner');
        alert('Something went wrong... please try again.');
      });
    };

    $scope.openMenuItem = function (id, schedule) {
    	$scope.cancel();
    	openModal('menuItemModal', id, schedule);
    }
}]);

app.filter('startFrom', function() {
    return function(input, start) {
        start = +start; //parse to int
        return input.slice(start);
    }
});