<?php

// app/Providers/RouteBindingServiceProvider.php

namespace App\Providers;

use mmghv\LumenRouteBinding\RouteBindingServiceProvider as BaseServiceProvider;

class RouteBindingServiceProvider extends BaseServiceProvider
{
    /**
     * Boot the service provider
     */
    public function boot()
    {
        // The binder instance
        $binder = $this->binder;
        $binder->implicitBind('App\Models');


        // Here we define our bindings
    }
}
