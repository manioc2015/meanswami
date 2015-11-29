'use strict';

var appRFM = angular.module("RestaurantFranchiseManageModule", ["ngResource", "ui.bootstrap", "angularSpinners"])
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
}).controller("RestaurantFranchiseManageController", ['$scope', '$resource', 'RestaurantFranchiseManageResource', function($scope, $resource, RestaurantFranchiseManageResource)
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
      //$scope.cancel();
      MenuItemService.setMenuItemId(id);
      MenuItemService.openModal();
      console.log(MenuItemService);
    }
}]);

appRFM.filter('startFrom', function() {
    return function(input, start) {
        start = +start; //parse to int
        return input.slice(start);
    }
});