'use strict';

var app = angular.module("RestaurantModule", ["ngResource"])
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
        data = data.data;
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
  });