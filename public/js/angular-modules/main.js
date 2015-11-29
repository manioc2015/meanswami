define(
	["angular", "angular-route", "angular-resource", "angular-sanitize", "angular.ng-modules",
	"ui-bootstrap","angular-spinners","angular-pubsub","MenuItem","RestaurantAndFranchiseManage"], 
		function(angular, route, resource, sanitize, modules, bootstrap, spinners, pubsub, menuitem, manage) {
	var appModal = angular.module("MenuItemModule", ["ngResource", "ui.bootstrap", "RestaurantFranchiseManageModule", "angularPubsub"]);
	var appRFM = angular.module("RestaurantFranchiseManageModule", ["ngResource", "ui.bootstrap", "angularSpinners", "angularPubsub"]);
});