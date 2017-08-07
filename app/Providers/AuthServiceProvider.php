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
        $this->registerProviderPrevixPolicies();

        $this->registerProductPolicies();
        $this->registerProductSellPricePolicies();

        $this->registerPartnerPulsaPolicies();
        $this->registerPartnerProductPolicies();
        $this->registerPartnerProductPurchPricePolicies();
        $this->registerPartnerServerPolicies();

        $this->registerAgent();

        $this->registerPalomaDepositTrx();

        $this->registerUsersPolicies();
    }

    public function registerProviderPolicies()
    {
        Gate::define('read-provider', function($user){
          return $user->hasAccess(['read-provider']);
        });
        Gate::define('create-provider', function($user){
          return $user->hasAccess(['create-provider']);
        });
        Gate::define('update-provider', function($user){
          return $user->hasAccess(['update-provider']);
        });
        Gate::define('activate-provider', function($user){
          return $user->hasAccess(['activate-provider']);
        });
        Gate::define('delete-provider', function($user){
          return $user->hasAccess(['delete-provider']);
        });
    }

    public function registerProviderPrevixPolicies()
    {
        Gate::define('read-provider-prefix', function($user){
          return $user->hasAccess(['read-provider-prefix']);
        });
        Gate::define('create-provider-prefix', function($user){
          return $user->hasAccess(['create-provider-prefix']);
        });
        Gate::define('update-provider-prefix', function($user){
          return $user->hasAccess(['update-provider-prefix']);
        });
        Gate::define('activate-provider-prefix', function($user){
          return $user->hasAccess(['activate-provider-prefix']);
        });
        Gate::define('delete-provider-prefix', function($user){
          return $user->hasAccess(['delete-provider-prefix']);
        });
    }

    public function registerProductPolicies()
    {
        Gate::define('read-product', function($user){
          return $user->hasAccess(['read-product']);
        });
        Gate::define('create-product', function($user){
          return $user->hasAccess(['create-product']);
        });
        Gate::define('update-product', function($user){
          return $user->hasAccess(['update-product']);
        });
        Gate::define('activate-product', function($user){
          return $user->hasAccess(['activate-product']);
        });
        Gate::define('delete-product', function($user){
          return $user->hasAccess(['delete-product']);
        });
    }

    public function registerProductSellPricePolicies()
    {
        Gate::define('read-product-sell-price', function($user){
          return $user->hasAccess(['read-product-sell-price']);
        });
        Gate::define('create-product-sell-price', function($user){
          return $user->hasAccess(['create-product-sell-price']);
        });
        Gate::define('update-product-sell-price', function($user){
          return $user->hasAccess(['update-product-sell-price']);
        });
        Gate::define('activate-product-sell-price', function($user){
          return $user->hasAccess(['activate-product-sell-price']);
        });
        Gate::define('delete-product-sell-price', function($user){
          return $user->hasAccess(['delete-product-sell-price']);
        });
    }

    public function registerPartnerPulsaPolicies()
    {
        Gate::define('read-partner-pulsa', function($user){
          return $user->hasAccess(['read-partner-pulsa']);
        });
        Gate::define('create-partner-pulsa', function($user){
          return $user->hasAccess(['create-partner-pulsa']);
        });
        Gate::define('update-partner-pulsa', function($user){
          return $user->hasAccess(['update-partner-pulsa']);
        });
        Gate::define('activate-partner-pulsa', function($user){
          return $user->hasAccess(['activate-partner-pulsa']);
        });
        Gate::define('delete-partner-pulsa', function($user){
          return $user->hasAccess(['delete-partner-pulsa']);
        });
    }

    public function registerPartnerProductPolicies()
    {
        Gate::define('read-partner-product', function($user){
          return $user->hasAccess(['read-partner-product']);
        });
        Gate::define('create-partner-product', function($user){
          return $user->hasAccess(['create-partner-product']);
        });
        Gate::define('update-partner-product', function($user){
          return $user->hasAccess(['update-partner-product']);
        });
        Gate::define('activate-partner-product', function($user){
          return $user->hasAccess(['activate-partner-product']);
        });
        Gate::define('delete-partner-product', function($user){
          return $user->hasAccess(['delete-partner-product']);
        });
    }

    public function registerPartnerProductPurchPricePolicies()
    {
        Gate::define('read-partner-product-purch-price', function($user){
          return $user->hasAccess(['read-partner-product-purch-price']);
        });
        Gate::define('create-partner-product-purch-price', function($user){
          return $user->hasAccess(['create-partner-product-purch-price']);
        });
        Gate::define('update-partner-product-purch-price', function($user){
          return $user->hasAccess(['update-partner-product-purch-price']);
        });
        Gate::define('activate-partner-product-purch-price', function($user){
          return $user->hasAccess(['activate-partner-product-purch-price']);
        });
        Gate::define('delete-partner-product-purch-price', function($user){
          return $user->hasAccess(['delete-partner-product-purch-price']);
        });
    }

    public function registerPartnerServerPolicies()
    {
        Gate::define('read-partner-server', function($user){
          return $user->hasAccess(['read-partner-server']);
        });
        Gate::define('create-partner-server', function($user){
          return $user->hasAccess(['create-partner-server']);
        });
        Gate::define('update-partner-server', function($user){
          return $user->hasAccess(['update-partner-server']);
        });
        Gate::define('activate-partner-server', function($user){
          return $user->hasAccess(['activate-partner-server']);
        });
        Gate::define('delete-partner-server', function($user){
          return $user->hasAccess(['delete-partner-server']);
        });
    }

    public function registerAgent()
    {
        Gate::define('read-agent', function($user){
          return $user->hasAccess(['read-agent']);
        });
        Gate::define('update-agent', function($user){
          return $user->hasAccess(['update-agent']);
        });
    }

    public function registerPalomaDepositTrx()
    {
        Gate::define('read-paloma-deposit-trx', function($user){
          return $user->hasAccess(['read-paloma-deposit-trx']);
        });
        Gate::define('create-paloma-deposit-trx', function($user){
          return $user->hasAccess(['create-paloma-deposit-trx']);
        });
        Gate::define('update-paloma-deposit-trx', function($user){
          return $user->hasAccess(['update-paloma-deposit-trx']);
        });
        Gate::define('activate-paloma-deposit-trx', function($user){
          return $user->hasAccess(['activate-paloma-deposit-trx']);
        });
        Gate::define('delete-paloma-deposit-trx', function($user){
          return $user->hasAccess(['delete-paloma-deposit-trx']);
        });
    }

    public function registerUsersPolicies()
    {
        Gate::define('read-user', function($user){
          return $user->hasAccess(['read-user']);
        });
        Gate::define('create-user', function($user){
          return $user->hasAccess(['create-user']);
        });
        Gate::define('update-user', function($user){
          return $user->hasAccess(['update-user']);
        });
        Gate::define('reset-user', function($user){
          return $user->hasAccess(['reset-user']);
        });
        Gate::define('activate-user', function($user){
          return $user->hasAccess(['activate-user']);
        });
        Gate::define('read-role', function($user){
          return $user->hasAccess(['read-role']);
        });
        Gate::define('update-role', function($user){
          return $user->hasAccess(['update-role']);
        });
        Gate::define('management-user', function($user){
          return $user->inRole('administrator');
        });
        Gate::define('management-role', function($user){
          return $user->inRole('administrator');
        });
    }
}
