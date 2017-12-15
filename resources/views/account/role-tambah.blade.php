@extends('layout.master')


@section('title')
  <title>STS | Account</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">
@endsection

@section('content')

@if(Session::has('berhasil'))
<script>
  window.setTimeout(function() {
    $(".alert-success").fadeTo(700, 0).slideUp(700, function(){
        $(this).remove();
    });
  }, 5000);
</script>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="alert alert-success alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
      </button>
      <strong>{{ Session::get('berhasil') }}</strong>
    </div>
  </div>
</div>
@endif


<div class="page-title">
  <div class="title_left">
    <h3>Create Role Task <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Create Access Role Task</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <form action="{{route('account.rolePost')}}" method="post" class="form-horizontal form-label-left" novalidate>
        {{ csrf_field() }}
      <div class="item form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Role Name <span class="required">*</span></label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <input type="text" name="name" class="form-control" value="{{ old('name') }}">
          @if($errors->has('name'))
          <code><span style="color:red; font-size:12px;">{{ $errors->first('name')}}</span></code>
          @endif
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Provider</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-provider]" {{ (collect(old('permissions'))->contains('read-provider')) ? 'checked="checked"' : '' }} value="true" /> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-provider]" {{ (collect(old('permissions'))->contains('create-provider')) ? 'checked="checked"' : '' }} value="true" /> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-provider]" {{ (collect(old('permissions'))->contains('update-provider')) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-provider]" {{ (collect(old('permissions'))->contains('delete-provider')) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Provider Prefix</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-provider-prefix]" {{ (collect(old('permissions'))->contains('read-provider-prefix')) ? 'checked="checked"' : '' }} value="true" /> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-provider-prefix]" {{ (collect(old('permissions'))->contains('create-provider-prefix')) ? 'checked="checked"' : '' }} value="true" /> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-provider-prefix]" {{ (collect(old('permissions'))->contains('update-provider-prefix')) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-provider-prefix]" {{ (collect(old('permissions'))->contains('delete-provider-prefix')) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-product]" {{ (collect(old('permissions'))->contains('read-product')) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-product]" {{ (collect(old('permissions'))->contains('create-product')) ? 'checked="checked"' : '' }} value="true"/> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-product]" {{ (collect(old('permissions'))->contains('update-product')) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-product]" {{ (collect(old('permissions'))->contains('delete-product')) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-product]" {{ (collect(old('permissions'))->contains('activate-product')) ? 'checked="checked"' : '' }} value="true" /> Status
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product Sell Price</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-product-sell-price]" {{ (collect(old('permissions'))->contains('read-product-sell-price')) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-product-sell-price]" {{ (collect(old('permissions'))->contains('create-product-sell-price')) ? 'checked="checked"' : '' }} value="true"/> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-product-sell-price]" {{ (collect(old('permissions'))->contains('update-product-sell-price')) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-product-sell-price]" {{ (collect(old('permissions'))->contains('delete-product-sell-price')) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-product-sell-price]" {{ (collect(old('permissions'))->contains('activate-product-sell-price')) ? 'checked="checked"' : '' }} value="true" /> Status
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Partner Pulsa</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-partner-pulsa]" {{ (collect(old('permissions'))->contains('read-partner-pulsa')) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-partner-pulsa]" {{ (collect(old('permissions'))->contains('create-partner-pulsa')) ? 'checked="checked"' : '' }} value="true"/> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-partner-pulsa]" {{ (collect(old('permissions'))->contains('update-partner-pulsa')) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-partner-pulsa]" {{ (collect(old('permissions'))->contains('delete-partner-pulsa')) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-partner-pulsa]" {{ (collect(old('permissions'))->contains('activate-partner-pulsa')) ? 'checked="checked"' : '' }} value="true" /> Status
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Partner Product</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-partner-product]" {{ (collect(old('permissions'))->contains('read-partner-product')) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-partner-product]" {{ (collect(old('permissions'))->contains('create-partner-product')) ? 'checked="checked"' : '' }} value="true"/> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-partner-product]" {{ (collect(old('permissions'))->contains('update-partner-product')) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-partner-product]" {{ (collect(old('permissions'))->contains('delete-partner-product')) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-partner-product]" {{ (collect(old('permissions'))->contains('activate-partner-product')) ? 'checked="checked"' : '' }} value="true" /> Status
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Partner Product Purch Price</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-partner-product-purch-price]" {{ (collect(old('permissions'))->contains('read-partner-product-purch-price')) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-partner-product-purch-price]" {{ (collect(old('permissions'))->contains('create-partner-product-purch-price')) ? 'checked="checked"' : '' }} value="true"/> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-partner-product-purch-price]" {{ (collect(old('permissions'))->contains('update-partner-product-purch-price')) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-partner-product-purch-price]" {{ (collect(old('permissions'))->contains('delete-partner-product-purch-price')) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-partner-product-purch-price]" {{ (collect(old('permissions'))->contains('activate-partner-product-purch-price')) ? 'checked="checked"' : '' }} value="true" /> Status
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Partner Server</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-partner-server]" {{ (collect(old('permissions'))->contains('read-partner-server')) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-partner-server]" {{ (collect(old('permissions'))->contains('create-partner-server')) ? 'checked="checked"' : '' }} value="true"/> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-partner-server]" {{ (collect(old('permissions'))->contains('update-partner-server')) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-partner-server]" {{ (collect(old('permissions'))->contains('delete-partner-server')) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Agent</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-agent]" {{ (collect(old('permissions'))->contains('read-agent')) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-agent]" {{ (collect(old('permissions'))->contains('update-agent')) ? 'checked="checked"' : '' }} value="true"/> Update
          </label>
          <br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-agent]" {{ (collect(old('permissions'))->contains('activate-agent')) ? 'checked="checked"' : '' }} value="true"/> Status
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Deposit Confirm</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-deposit-confirm]" {{ (collect(old('permissions'))->contains('read-deposit-confirm')) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[confirm-deposit-confirm]" {{ (collect(old('permissions'))->contains('confirm-deposit-confirm')) ? 'checked="checked"' : '' }} value="true"/> Confirm
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[read-deposit-unconfirm]" {{ (collect(old('permissions'))->contains('read-deposit-unconfirm')) ? 'checked="checked"' : '' }} value="true"/> All Unconfirmed Unique Code
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Deposit Void</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-deposit-reversal]" {{ (collect(old('permissions'))->contains('read-deposit-reversal')) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[confirm-deposit-reversal]" {{ (collect(old('permissions'))->contains('confirm-deposit-reversal')) ? 'checked="checked"' : '' }} value="true"/> Confirm
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Deposit Transaction</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-deposit-trx]" {{ (collect(old('permissions'))->contains('read-deposit-trx')) ? 'checked="checked"' : '' }} value="true"/> Read
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Report</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[report-supplier-pkp]" {{ (collect(old('permissions'))->contains('report-supplier-pkp')) ? 'checked="checked"' : '' }} value="true" /> Sales By Supplier PKP
          </label><br>
           <label>
            <input type="checkbox" class="flat" name="permissions[report-supplier-non-pkp]" {{ (collect(old('permissions'))->contains('report-supplier-non-pkp')) ? 'checked="checked"' : '' }} value="true" /> Sales By Supplier Non PKP
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[report-agent]" {{ (collect(old('permissions'))->contains('report-agent')) ? 'checked="checked"' : '' }} value="true" /> Sales By Agent
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[report-provider]" {{ (collect(old('permissions'))->contains('report-provider')) ? 'checked="checked"' : '' }} value="true" /> Sales By Provider
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[report-topup-deposit-partner]" {{ (collect(old('permissions'))->contains('report-topup-deposit-partner')) ? 'checked="checked"' : '' }} value="true" /> Topup Deposit Partner
          </label><br>
           <label>
            <input type="checkbox" class="flat" name="permissions[report-inquiry-agent]" {{ (collect(old('permissions'))->contains('report-inquiry-agent')) ? 'checked="checked"' : '' }} value="true" /> Inquiry Pesanan Agent
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[report-rekap-sales-harian-agent]" {{ (collect(old('permissions'))->contains('report-rekap-sales-harian-agent')) ? 'checked="checked"' : '' }} value="true" /> Rekap Sales Harian Agent
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[report-weekly-sales-summary]" {{ (collect(old('permissions'))->contains('report-weekly-sales-summary')) ? 'checked="checked"' : '' }} value="true" /> Weekly Sales Summary
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[report-saldo-deposit-agent]" {{ (collect(old('permissions'))->contains('report-saldo-deposit-agent')) ? 'checked="checked"' : '' }} value="true" /> Saldo Deposit Agent
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Users</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-user]" {{ (collect(old('permissions'))->contains('read-user')) ? 'checked="checked"' : '' }} value="true" /> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-user]" {{ (collect(old('permissions'))->contains('create-user')) ? 'checked="checked"' : '' }} value="true" /> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-user]" {{ (collect(old('permissions'))->contains('update-user')) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[reset-user]" {{ (collect(old('permissions'))->contains('reset-user')) ? 'checked="checked"' : '' }} value="true" /> Reset
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-user]" {{ (collect(old('permissions'))->contains('activate-user')) ? 'checked="checked"' : '' }} value="true" /> Status
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Roles</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-role]" {{ (collect(old('permissions'))->contains('read-role')) ? 'checked="checked"' : '' }} value="true" /> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-role]" {{ (collect(old('permissions'))->contains('create-role')) ? 'checked="checked"' : '' }} value="true" /> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-role]" {{ (collect(old('permissions'))->contains('update-role')) ? 'checked="checked"' : '' }} value="true" /> Update
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
          <a href="{{ route('account.role') }}" class="btn btn-primary">Cancel</a>
          <button id="send" type="submit" class="btn btn-success">Submit</button>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>
</div>

@endsection

@section('script')
<script src="{{ asset('amadeo/vendors/validator/validator.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('amadeo/vendors/iCheck/icheck.min.js')}}"></script>
<script src="{{ asset('amadeo/vendors/switchery/dist/switchery.min.js')}}"></script>

<script type="text/javascript">
  $("#role").select2({
    placeholder: "Choose Role",
    allowClear: true
  });
</script>
@endsection
