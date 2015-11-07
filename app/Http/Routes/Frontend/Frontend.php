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
	get('/restaurant/signup/lookup', 'SignupController@getLookup')->name('signup.signup');
	post('restaurant/signup/lookup', 'SignupController@postLookup')->name('signup.signup');
	post('restaurant/signup/submit', 'SignupController@postSubmit')->name('signup.signup');
	$router->controller('signup', 'SignupController');
});