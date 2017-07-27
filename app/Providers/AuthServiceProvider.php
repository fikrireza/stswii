<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerProviderPolicies();
        //
    }

    public function registerProviderPolicies()
    {
        Gate::define('view-provider', function($user){
          return $user->hasAccess(['view-provider']);
        });
        Gate::define('create-provider', function($user){
          return $user->hasAccess(['create-provider']);
        });
        Gate::define('update-provider', function($user){
          return $user->hasAccess(['update-povider']);
        });
        Gate::define('activate-provider', function($user){
          return $user->hasAccess(['activate-provider']);
        });
        Gate::define('delete-provider', function($user){
          return $user->hasAccess(['delete-provider']);
        });
        Gate::define('see-all-provider', function($user){
          return $user->inRole('administrator');
        });
    }
}
