<?php

/**
 * Frontend Controllers
 */
get('/', 'FrontendController@index')->name('home');
get('macros', 'FrontendController@macros');

/**
 * These frontend controllers require the user to be logged in
 */
$router->group(['middleware' => 'auth'], function ()
{
	get('dashboard', 'DashboardController@index')->name('frontend.dashboard');
	get('profile/edit', 'ProfileController@edit')->name('frontend.profile.edit');
	patch('profile/update', 'ProfileController@update')->name('frontend.profile.update');
});

$router->group(['namespace' => 'Restaurant'], function () use ($router) {
	get('restaurant/signup/lookup', 'SignupController@getLookup')->name('signup.lookup');
	post('restaurant/signup/lookup', 'SignupController@postLookup')->name('signup.lookupPost');
	post('restaurant/signup/addRestaurant', 'SignupController@postAddRestaurant')->name('signup.addRestaurant');
	post('restaurant/signup/addClient', 'SignupController@postAddClient')->name('signup.addClient');
	get('restaurant/signup/save', 'SignupController@getSave')->name('signup.save');

	$router->controller('signup', 'SignupController');
});

$router->group(['middleware' => ['auth', 'access.routeNeedsPermission:basic_client_permissions'], 'namespace' => 'Restaurant'], function () use ($router) {
	get('restaurant/manage', 'ManageController@getIndex')->name('restaurant.manage.index');
	get('restaurant/manage/stats', 'ManageController@getStats')->name('restaurant.manage.stats');
	get('restaurant/manage/franchise', 'ManageController@getFranchise')->name('restaurant.manage.franchise');
	get('restaurant/manage/restaurant', 'ManageController@getRestaurant')->name('restaurant.manage.restaurant');
	post('restaurant/save', 'ManageController@postRestaurant')->name('restaurant.save');
	get('restaurant/menuitem/lookup', 'ManageController@getMenuItem')->name('restaurant.menuitem.lookup');
	get('restaurant/menuitem/lookupByProperty', 'ManageController@getMenuItemsByProperty')->name('restaurant.menuitem.lookupByProperty');
	get('restaurant/properties/lookup', 'ManageController@getClientProperties')->name('restaurant.properties.lookup');
});

$router->group(['middleware' => ['auth', 'access.routeNeedsPermission:create_menu_items'], 'namespace' => 'Restaurant'], function () use ($router) {
	post('restaurant/menuitem/save', 'ManageController@postMenuItem')->name('restaurant.menuitem.save');
	post('restaurant/menuitem/schedule', 'ManageController@postMenuItemSchedule')->name('restaurant.menuitem.schedule');
});

$router->group(['middleware' => 'auth', 'namespace' => 'Client'], function () use ($router) {
	get('client/profile/update', 'ProfileController@update')->name('client.profile.update');
	post('client/profile/save', 'ProfileController@postSave')->name('client.profile.save');
});