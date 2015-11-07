<?php
namespace App\Providers;

use Dingo\Api\Auth\Auth;
use Dingo\Api\Auth\Provider\OAuth2;
use Illuminate\Support\ServiceProvider;
use App\Models\Access\User\User;
use LucaDegasperi\OAuth2Server\Storage;

class OAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->app[Auth::class]->extend('oauth', function ($app) {
            $provider = new OAuth2($app['oauth2-server.authorizer']->getChecker());

            $provider->setUserResolver(function ($id) {
                return User::findOrFail($id);
            });

            $provider->setClientResolver(function ($id) {
                $resolver = $app->make(FluentClient::class);
                return $resolver->get($id);  
            });

            return $provider;
        });
    }

    public function register()
    {
        //
    }
}
