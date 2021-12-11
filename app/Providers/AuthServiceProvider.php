<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Todo;
use Illuminate\Support\Facades\Gate;
use App\Policies\TodoPolicy;
use App\Policies\UserPolicy;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    // /**
    //  * The policy mappings for the application.
    //  *
    //  * @var array
    //  */
    // protected $policies = [
    //     Todo::class => TodoPolicy::class,
    //     User::class => UserPolicy::class,
    // ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        //Gate::policy(User::class, UserPolicy::class);
        Gate::policy("App\Models\Todo", "App\Policies\TodoPolicy");
        Gate::policy("App\Models\User", "App\Policies\UserPolicy");

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });
    }
}
