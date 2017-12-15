<div class="col-md-3 left_col menu_fixed">
  <div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
      <a href="" class="site_title"> <span>Dashboard</span></a>
    </div>

    <div class="clearfix"></div>

    <div class="profile">
      <div class="profile_pic">
        <img src="{{ asset('amadeo/images/profile/').'/'.Auth::user()->avatar }}" alt="..." class="img-circle profile_img">
      </div>
      <div class="profile_info">
        <span>Hai,</span>
        <h2>{{ Auth::user()->name }}</h2>
      </div>
    </div>

    <br />

    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>General</h3>
        <ul class="nav side-menu">
          <li class="{{ Route::is('home*') ? 'active' : '' }}">
            <a href="{{ route('home.index') }}"><i class="fa fa-home"></i> Home </a>
          </li>
          @if (Auth::user()->can('read-provider') || Auth::user()->can('read-provider-prefix'))
          <li class="{{ Route::is('provider*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-phone"></i> Manage Provider <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('provider*') ? 'display: block;' : '' }}">
              @can('read-provider')
              <li class="{{ Route::is('provider.index') ? 'current-page' : '' }}">
                <a href="{{ route('provider.index') }}">Provider</a>
              </li>
              @endcan
              @can('read-provider-prefix')
              <li class="{{ Route::is('provider-prefix.index') ? 'current-page' : '' }}">
                <a href="{{ route('provider-prefix.index') }}">Provider Prefix</a>
              </li>
              @endcan
            </ul>
          </li>
          @endif
          @if (Auth::user()->can('read-product') || Auth::user()->can('read-product-sell-price'))
          <li class="{{ Route::is('product.*') ? 'active' : '' }}{{ Route::is('product-*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-beer"></i> Manage Product <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('product.*') ? 'display: block;' : '' }}{{ Route::is('product-*') ? 'display: block;' : '' }}">
              @can('read-product')
              <li class="{{ Route::is('product.*') ? 'current-page' : '' }}"><a href="{{ route('product.index') }}">Product</a></li>
              @endcan
               @can('read-product-mlm')
              <li class="{{ Route::is('product-mlm.*') ? 'current-page' : '' }}"><a href="{{ route('product-mlm.index') }}">Product Mlm</a></li>
              @endcan
              @can('read-product-sell-price')
              <li class="{{ Route::is('product-sell-price.*')  ? 'current-page' : '' }}"><a href="{{ route('product-sell-price.index') }}">Product Sell Price</a></li>
              @endcan
              @can('read-product-sell-price-mlm')
              <li class="{{ Route::is('product-sell-price-mlm.*') ? 'current-page' : '' }}"><a href="{{ route('product-sell-price-mlm.index') }}">Product Sell Price Mlm</a></li>
              @endcan
            </ul>
          </li>
          @endif
          @if(Auth::user()->can('read-partner-pulsa') || Auth::user()->can('read-partner-product') || Auth::user()->can('read-partner-product-purch-price') || Auth::user()->can('read-partner-server'))
          <li class="{{ Route::is('partner-product*') ? 'active' : '' }}{{ Route::is('partner-pulsa*') ? 'active' : '' }}{{ Route::is('partner-server*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-anchor"></i> Manage Supplier <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('partner-product*') ? 'display: block;' : '' }}{{ Route::is('partner-pulsa*') ? 'display: block;' : '' }}">
              @can('read-partner-pulsa')
              <li class="{{ Route::is('partner-pulsa*') ? 'current-page' : '' }}">
                <a href="{{ route('partner-pulsa.index') }}">Supplier Pulsa</a>
              </li>
              @endcan
              @can('read-partner-product')
              <li class="{{ Route::is('partner-product.*') ? 'current-page' : '' }}">
                <a href="{{ route('partner-product.index') }}">Supplier Product</a>
              </li>
              @endcan
              @can('read-partner-product-purch-price')
              <li class="{{ Route::is('partner-product-purch-price*') ? 'current-page' : '' }}">
                <a href="{{ route('partner-product-purch-price.index') }}">Supplier Product Purch Price</a>
              </li>
              @endcan
              @can('read-partner-server')
              <li class="{{ Route::is('partner-server*') ? 'current-page' : '' }}">
                <a href="{{ route('partner-server.index') }}">Supplier Server</a>
              </li>
              @endcan
            </ul>
          </li>
          @endif
          @if (Auth::user()->can('read-agent'))
          <li class="{{ Route::is('agent*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-suitcase"></i> Manage Agent <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('agent*') ? 'display: block;' : '' }}">
              @can('read-agent')
              <li class="{{ Route::is('agent.index') ? 'current-page' : '' }}">
                <a href="{{ route('agent.index') }}">Agent</a>
              </li>
              @endcan
            </ul>
          </li>
          @endif
          @if (Auth::user()->can('read-deposit-confirm') || Auth::user()->can('read-deposit-reversal') || Auth::user()->can('read-deposit-unconfirm'))
          <li class="{{ Route::is('deposit-agent*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-money"></i> Agent Deposit <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('deposit-agent*') ? 'display: block;' : '' }}">
              @can('read-deposit-confirm')
              <li class="{{ Route::is('deposit-agent-confirm*') ? 'current-page' : '' }}">
                <a href="{{ route('deposit-agent-confirm.index') }}">Deposit Agent Confirm</a>
              </li>
              @endcan
              @can('read-deposit-reversal')
              <li class="{{ Route::is('deposit-agent-reversal*') ? 'current-page' : '' }}">
                <a href="{{ route('deposit-agent-reversal.index') }}">Deposit Agent Void</a>
              </li>
              @endcan
              @can('read-deposit-unconfirm')
              <li class="{{ Route::is('deposit-agent-unconfirm*') ? 'current-page' : '' }}">
                <a href="{{ route('deposit-agent-unconfirm.index') }}">Unconfirmed Unique Code</a>
              </li>
              @endcan
              @can('read-inquiry-mutasi-rekening-mandiri')
              <li class="{{ Route::is('inquiry-mutasi-rekening-mandiri') ? 'current-page' : '' }}">
                <a href="{{ route('inquiry-mutasi-rekening-mandiri.index') }}">Mutasi Rek Mandiri</a>
              </li>
              @endcan
            </ul>
          </li>
          @endif
          @if (Auth::user()->can('read-deposit-trx'))
          <li class="{{ Route::is('palomaDeposit*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-money"></i>Paloma Deposit <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('palomaDeposit*') ? 'display: block;' : '' }}">
              @can('read-deposit-trx')
                <li class="{{ Route::is('palomaDeposit*') ? 'current-page' : '' }}">
                  <a href="{{ route('palomaDeposit.index') }}">Deposit Transaction</a>
                </li>
              @endcan
            </ul>
          </li>
          @endif
          @if (Auth::user()->can('read-sales-deposit-transaction') || Auth::user()->can('read-salesman'))
          <li class="{{ Route::is('salesDeposit*') || Route::is('salesman*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-money"></i>Sales Deposit <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('salesDeposit*') ? 'display: block;' : '' }}">
                @if(Auth::user()->can('read-salesman'))
                <li class="{{ Route::is('salesman*') ? 'current-page' : '' }}">
                  <a href="{{ route('salesman.index') }}">Salesman</a>
                </li>
                @endif
                @if(Auth::user()->can('read-sales-deposit-transaction'))
                <li class="{{ Route::is('salesDepositTransaction*') ? 'current-page' : '' }}">
                  <a href="{{ route('salesDepositTransaction.index') }}">Deposit Transaction</a>
                </li>
                @endif
            </ul>
          </li>
          @endif

        </ul>
      </div>
      <div class="menu_section">
        <h3>Extra</h3>
        <ul class="nav side-menu">
          @if (Auth::user()->can('report-supplier') || Auth::user()->can('report-agent') || Auth::user()->can('report-provider') || Auth::user()->can('report-topup-deposit-partner') || Auth::user()->can('report-inquiry-agent') )
          <li class="{{ Route::is('report*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-file-text-o"></i> Report <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('report*') ? 'display: block;' : '' }}">
              @can('report-supplier-pkp')
              <li class="{{ Route::is('report.bySupplierPkp') ? 'current-page' : '' }}"><a href="{{ route('report.bySupplierPkp') }}">Sales By Supplier PKP</a></li>
              @endcan
              @can('report-supplier-non-pkp')
              <li class="{{ Route::is('report.bySupplierNonPkp') ? 'current-page' : '' }}"><a href="{{ route('report.bySupplierNonPkp') }}">Sales By Supplier Non PKP</a></li>
              @endcan
              @can('report-agent')
              <li class="{{ Route::is('report.byAgent') ? 'current-page' : '' }}"><a href="{{ route('report.byAgent') }}">Sales By Agent</a></li>
              @endcan
              @can('report-agent-mlm')
              <li class="{{ Route::is('report.byAgentMlm') ? 'current-page' : '' }}"><a href="{{ route('report.byAgentMlm') }}">Sales By Agent MLM</a></li>
              @endcan
              @can('report-provider')
              <li class="{{ Route::is('report.byProvider') ? 'current-page' : '' }}"><a href="{{ route('report.byProvider') }}">Sales By Provider</a></li>
              @endcan
              @can('report-topup-deposit-partner')
              <li class="{{ Route::is('report.byTopUpDepositPartner') ? 'current-page' : '' }}"><a href="{{ route('report.byTopUpDepositPartner') }}">Topup Deposit Partner</a></li>
              @endcan
              @can('report-deposit-harian-agent')
              <li class="{{ Route::is('report.byDepositharianAgent') ? 'current-page' : '' }}"><a href="{{ route('report.byDepositHarianAgent') }}">Deposit Harian Agent</a></li>
              @endcan
              @can('report-inquiry-agent')
              <li class="{{ Route::is('inquiry-pesanan-agent') ? 'current-page' : '' }}"><a href="{{ route('report.inquiry-pesanan-agent-index') }}">Inquiry Pesanan Agent</a></li>
              @endcan
              @can('report-rekap-sales-harian-agent')
              <li class="{{ Route::is('report.rekap-sales-harian-agent') ? 'current-page' : '' }}"><a href="{{ route('report.byRekapSalesHarianAgent') }}">Rekap Sales Harian Agent</a></li>
              @endcan
              @can('report-weekly-sales-summary')
              <li class="{{ Route::is('report.byWeeklySalesSummary') ? 'current-page' : '' }}"><a href="{{ route('report.byWeeklySalesSummary') }}">Weekly Sales Summary</a></li>
              @endcan
              @can('report-saldo-deposit-agent')
              <li class="{{ Route::is('report.bySaldoDepositAgent') ? 'current-page' : '' }}"><a href="{{ route('report.bySaldoDepositAgent') }}">Saldo Deposit Agent</a></li>
              @endcan
              @can('report-sales-deposit')
              <li class="{{ Route::is('report.bySalesDeposit') ? 'current-page' : '' }}"><a href="{{ route('report.bySalesDeposit') }}">Sales Deposit</a></li>
              @endcan              
              @can('report-data-agent-not-active')
              <li class="{{ Route::is('report.byDataAgentNotActive') ? 'current-page' : '' }}"><a href="{{ route('report.byDataAgentNotActive') }}">Data Agent Tidak Active</a></li>
              @endcan
              @can('report-agent-member-paloma')
              <li class="{{ Route::is('report.byAgentMemberPaloma') ? 'current-page' : '' }}"><a href="{{ route('report.byAgentMemberPaloma') }}">Agent Member Paloma</a></li>
              @endcan
              @can('report-statistik-transaksi-error')
              <li class="{{ Route::is('report.byStatistikTransaksiError') ? 'current-page' : '' }}"><a href="{{ route('report.byStatistikTransaksiError') }}">Statistik Transaksi Error</a></li>
              @endcan
              @can('report-perubahan-status-manual')
              <li class="{{ Route::is('report.byPerubahanStatusManual') ? 'current-page' : '' }}"><a href="{{ route('report.byPerubahanStatusManual') }}">Perubahan Status Manual</a></li>
              @endcan
            </ul>
          </li>
          @endif
          @can('management-user')
          <li class="{{ Route::is('account*') ? 'active' : '' }}">
            <a>
              <i class="fa fa-users"></i> Manage Account <span class="fa fa-chevron-down"></span>
            </a>
            <ul class="nav child_menu" style="{{ Route::is('account*') ? 'display: block;' : '' }}">
              <li class="{{ Route::is('account.index') ? 'current-page' : '' }}"><a href="{{ route('account.index') }}">Users</a></li>
              @can('management-role')
              <li class="{{ Route::is('account.role') ? 'current-page' : '' }}"><a href="{{ route('account.role') }}">Role Task</a></li>
              @endcan
            </ul>
          </li>
          @endcan
        </ul>
      </div>
    </div>

    {{-- <div class="sidebar-footer hidden-small">
      <a href="" data-toggle="tooltip" data-placement="top" title="Users">
        <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
      </a>
      <a href="" data-toggle="tooltip" data-placement="top" title="Inbox">
        <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
      </a>
      <a href="" data-toggle="tooltip" data-placement="top" title="Profile">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
      </a>
      <a href="" data-toggle="tooltip" data-placement="top" title="Logout">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
      </a>
    </div> --}}
  </div>
</div>

<!-- <style type="text/css">
  #dataTables>tbody>tr:nth-last-child(1)>td:nth-last-child(1)>a:nth-child(2){
    border: thick dashed red;
  }

</style> -->
