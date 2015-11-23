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

$router->group(['middleware' => 'auth', 'namespace' => 'Restaurant'], function () use ($router) {
	get('restaurant/manage', 'ManageController@getIndex')->name('manage.index');
});
