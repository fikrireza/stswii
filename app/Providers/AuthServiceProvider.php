<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *f
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
        $this->registerProductMlmPolicies();
        $this->registerProductSellPricePolicies();
        $this->registerProductSellPriceMlmPolicies();

        $this->registerPartnerPulsaPolicies();
        $this->registerPartnerProductPolicies();
        $this->registerPartnerProductPurchPricePolicies();
        $this->registerPartnerServerPolicies();

        $this->registerAgent();

        $this->registerSalesman();
        $this->registerSalesDepositTransaction();

        $this->registerDepositAgentConfirm();
        $this->registerDepositAgentReversal();
        $this->registerDepositTrx();
        $this->inquiryMutasiRekeningMandiriPolicies();

        $this->registerReportPolicies();

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
        Gate::define('sort-number-product', function($user){
          return $user->hasAccess(['sort-number-product']);
        });
    }

    public function registerProductMlmPolicies()
    {
        Gate::define('read-product-mlm', function($user){
          return $user->hasAccess(['read-product-mlm']);
        });
        Gate::define('create-product-mlm', function($user){
          return $user->hasAccess(['create-product-mlm']);
        });      
        Gate::define('delete-product-mlm', function($user){
          return $user->hasAccess(['delete-product-mlm']);
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

    public function registerProductSellPriceMlmPolicies()
    {
        Gate::define('read-product-sell-price-mlm', function($user){
          return $user->hasAccess(['read-product-sell-price-mlm']);
        });
        Gate::define('create-product-sell-price-mlm', function($user){
          return $user->hasAccess(['create-product-sell-price-mlm']);
        });
        Gate::define('update-product-sell-price-mlm', function($user){
          return $user->hasAccess(['update-product-sell-price-mlm']);
        });
       /* Gate::define('activate-product-sell-price-mlm', function($user){
          return $user->hasAccess(['activate-product-sell-price-mlm']);
        });*/
        Gate::define('delete-product-sell-price-mlm', function($user){
          return $user->hasAccess(['delete-product-sell-price-mlm']);
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
        Gate::define('activate-agent', function($user){
          return $user->hasAccess(['activate-agent']);
        });
    }

    public function registerSalesman()
    {
        Gate::define('read-salesman', function($user){
          return $user->hasAccess(['read-salesman']);
        });
        Gate::define('create-salesman', function($user){
          return $user->hasAccess(['create-salesman']);
        });
        Gate::define('update-salesman', function($user){
          return $user->hasAccess(['update-salesman']);
        });
        Gate::define('activate-salesman', function($user){
          return $user->hasAccess(['activate-salesman']);
        });
    }

    public function registerSalesDepositTransaction()
    {
        Gate::define('read-sales-deposit-transaction', function($user){
          return $user->hasAccess(['read-sales-deposit-transaction']);
        });
        Gate::define('update-sales-deposit-transaction', function($user){
          return $user->hasAccess(['update-sales-deposit-transaction']);
        });
        Gate::define('set-sudah-setor-sales-deposit-transaction', function($user){
          return $user->hasAccess(['set-sudah-setor-sales-deposit-transaction']);
        });
        Gate::define('set-belum-setor-sales-deposit-transaction', function($user){
          return $user->hasAccess(['set-belum-setor-sales-deposit-transaction']);
        });
    }

    public function registerDepositAgentConfirm()
    {
        Gate::define('read-deposit-confirm', function($user){
          return $user->hasAccess(['read-deposit-confirm']);
        });
        Gate::define('confirm-deposit-confirm', function($user){
          return $user->hasAccess(['confirm-deposit-confirm']);
        });
        Gate::define('read-deposit-unconfirm', function($user){
          return $user->hasAccess(['read-deposit-unconfirm']);
        });
    }

    public function registerDepositAgentReversal()
    {
        Gate::define('read-deposit-reversal', function($user){
          return $user->hasAccess(['read-deposit-reversal']);
        });
        Gate::define('confirm-deposit-reversal', function($user){
          return $user->hasAccess(['confirm-deposit-reversal']);
        });
    }

    public function registerDepositTrx()
    {
        Gate::define('read-deposit-trx', function($user){
          return $user->hasAccess(['read-deposit-trx']);
        });
    }

    public function inquiryMutasiRekeningMandiriPolicies()
    {
        Gate::define('read-inquiry-mutasi-rekening-mandiri', function($user){
          return $user->hasAccess(['read-inquiry-mutasi-rekening-mandiri']);
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
        Gate::define('create-role', function($user){
          return $user->hasAccess(['create-role']);
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

    public function registerReportPolicies()
    {
        Gate::define('report-supplier-pkp', function($user){
          return $user->hasAccess(['report-supplier-pkp']);
        });
        Gate::define('report-supplier-non-pkp', function($user){
          return $user->hasAccess(['report-supplier-non-pkp']);
        });
        Gate::define('report-agent', function($user){
          return $user->hasAccess(['report-agent']);
        });
        Gate::define('report-agent-mlm', function($user){
          return $user->hasAccess(['report-agent-mlm']);
        });
        Gate::define('report-provider', function($user){
          return $user->hasAccess(['report-provider']);
        });
        Gate::define('report-topup-deposit-partner', function($user){
          return $user->hasAccess(['report-topup-deposit-partner']);
        });
        Gate::define('report-deposit-harian-agent', function($user){
          return $user->hasAccess(['report-deposit-harian-agent']);
        });
        Gate::define('report-inquiry-agent', function($user){
          return $user->hasAccess(['report-inquiry-agent']);
        });
        Gate::define('set-sukses-inquiry-pesanan-agent', function($user){
          return $user->hasAccess(['set-sukses-inquiry-pesanan-agent']);
        });
        Gate::define('set-gagal-inquiry-pesanan-agent', function($user){
          return $user->hasAccess(['set-gagal-inquiry-pesanan-agent']);
        });
        Gate::define('report-rekap-sales-harian-agent', function($user){
          return $user->hasAccess(['report-rekap-sales-harian-agent']);
        });
        Gate::define('report-weekly-sales-summary', function($user){
          return $user->hasAccess(['report-weekly-sales-summary']);
        });
        Gate::define('report-saldo-deposit-agent', function($user){
          return $user->hasAccess(['report-saldo-deposit-agent']);
        });
        Gate::define('report-sales-deposit', function($user){
          return $user->hasAccess(['report-sales-deposit']);
        });        
        Gate::define('report-data-agent-not-active', function($user){
          return $user->hasAccess(['report-data-agent-not-active']);
        });
        Gate::define('report-agent-member-paloma', function($user){
          return $user->hasAccess(['report-agent-member-paloma']);
        });
        Gate::define('report-statistik-transaksi-error', function($user){
          return $user->hasAccess(['report-statistik-transaksi-error']);
        });
        Gate::define('report-perubahan-status-manual', function($user){
          return $user->hasAccess(['report-perubahan-status-manual']);
        });
    }

}
