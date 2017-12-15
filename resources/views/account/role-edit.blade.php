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

@if(Session::has('gagal'))
<script>
  window.setTimeout(function() {
    $(".alert-danger").fadeTo(700, 0).slideUp(700, function(){
        $(this).remove();
    });
  }, 5000);
</script>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
      </button>
      <strong>{{ Session::get('gagal') }}</strong>
    </div>
  </div>
</div>
@endif


<div class="page-title">
  <div class="title_left">
    <h3>Edit Role Task <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Edit Access Role Task</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <form action="{{route('account.roleEdit')}}" method="post" class="form-horizontal form-label-left" novalidate>
        {{ csrf_field() }}
      <div class="item form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        <input type="hidden" name="slug" value="{{ $getRole->slug }}">
        <input type="hidden" name="id" value="{{ $getRole->id }}">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Role Name <span class="required">*</span></label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <input type="text" name="name" class="form-control" value="{{ $getRole->name }}" readonly>
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
            <input type="checkbox" class="flat" name="permissions[read-provider]" {{ in_array('read-provider',$can) ? 'checked="checked"' : '' }} value="true" /> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-provider]" {{ in_array('create-provider',$can) ? 'checked="checked"' : '' }} value="true" /> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-provider]" {{ in_array('update-provider',$can) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-provider]" {{ in_array('delete-provider',$can) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Provider Prefix</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-provider-prefix]" {{ in_array('read-provider-prefix',$can) ? 'checked="checked"' : '' }} value="true" /> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-provider-prefix]" {{ in_array('create-provider-prefix',$can) ? 'checked="checked"' : '' }} value="true" /> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-provider-prefix]" {{ in_array('update-provider-prefix',$can) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-provider-prefix]" {{ in_array('delete-provider-prefix',$can) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-product]" {{ in_array('read-product',$can) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-product]" {{ in_array('create-product',$can) ? 'checked="checked"' : '' }} value="true"/> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-product]" {{ in_array('update-product',$can) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-product]" {{ in_array('delete-product',$can) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-product]" {{ in_array('activate-product',$can) ? 'checked="checked"' : '' }} value="true" /> Status
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[sort-number-product]" {{ in_array('sort-number-product',$can) ? 'checked="checked"' : '' }} value="true" /> Sort Number
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product Sell Price</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-product-sell-price]" {{ in_array('read-product-sell-price',$can) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-product-sell-price]" {{ in_array('create-product-sell-price',$can) ? 'checked="checked"' : '' }} value="true"/> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-product-sell-price]" {{ in_array('update-product-sell-price',$can) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-product-sell-price]" {{ in_array('delete-product-sell-price',$can) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-product-sell-price]" {{ in_array('activate-product-sell-price',$can) ? 'checked="checked"' : '' }} value="true" /> Status
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Supplier Pulsa</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-partner-pulsa]" {{ in_array('read-partner-pulsa',$can) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-partner-pulsa]" {{ in_array('create-partner-pulsa',$can) ? 'checked="checked"' : '' }} value="true"/> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-partner-pulsa]" {{ in_array('update-partner-pulsa',$can) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-partner-pulsa]" {{ in_array('delete-partner-pulsa',$can) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-partner-pulsa]" {{ in_array('activate-partner-pulsa',$can) ? 'checked="checked"' : '' }} value="true" /> Status
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Supplier Product</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-partner-product]" {{ in_array('read-partner-product',$can) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-partner-product]" {{ in_array('create-partner-product',$can) ? 'checked="checked"' : '' }} value="true"/> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-partner-product]" {{ in_array('update-partner-product',$can) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-partner-product]" {{ in_array('delete-partner-product',$can) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-partner-product]" {{ in_array('activate-partner-product',$can) ? 'checked="checked"' : '' }} value="true" /> Status
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Supplier Product Purch Price</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-partner-product-purch-price]" {{ in_array('read-partner-product-purch-price',$can) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-partner-product-purch-price]" {{ in_array('create-partner-product-purch-price',$can) ? 'checked="checked"' : '' }} value="true"/> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-partner-product-purch-price]" {{ in_array('update-partner-product-purch-price',$can) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-partner-product-purch-price]" {{ in_array('delete-partner-product-purch-price',$can) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-partner-product-purch-price]" {{ in_array('activate-partner-product-purch-price',$can) ? 'checked="checked"' : '' }} value="true" /> Status
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Supplier Server</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-partner-server]" {{ in_array('read-partner-server',$can) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-partner-server]" {{ in_array('create-partner-server',$can) ? 'checked="checked"' : '' }} value="true"/> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-partner-server]" {{ in_array('update-partner-server',$can) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[delete-partner-server]" {{ in_array('delete-partner-server',$can) ? 'checked="checked"' : '' }} value="true" /> Delete
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Agent</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-agent]" {{ in_array('read-agent',$can) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-agent]" {{ in_array('update-agent',$can) ? 'checked="checked"' : '' }} value="true"/> Update
          </label>
          <br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-agent]" {{ in_array('activate-agent',$can) ? 'checked="checked"' : '' }} value="true"/> Status
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Deposit Confirm</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-deposit-confirm]" {{ in_array('read-deposit-confirm',$can) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[confirm-deposit-confirm]" {{ in_array('confirm-deposit-confirm',$can) ? 'checked="checked"' : '' }} value="true"/> Confirm
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[read-deposit-unconfirm]" {{ in_array('read-deposit-unconfirm',$can) ? 'checked="checked"' : '' }} value="true"/> All Unconfirmed Unique Code
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Deposit Void</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-deposit-reversal]" {{ in_array('read-deposit-reversal',$can) ? 'checked="checked"' : '' }} value="true"/> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[confirm-deposit-reversal]" {{ in_array('confirm-deposit-reversal',$can) ? 'checked="checked"' : '' }} value="true"/> Confirm
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Deposit Transaction</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-deposit-trx]" {{ in_array('read-deposit-trx',$can) ? 'checked="checked"' : '' }} value="true"/> Read
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Report</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[report-supplier-pkp]" {{ in_array('report-supplier-pkp',$can) ? 'checked="checked"' : '' }} value="true" /> Sales By Supplier PKP
          </label><br>
           <label>
            <input type="checkbox" class="flat" name="permissions[report-supplier-non-pkp]" {{ in_array('report-supplier-non-pkp',$can) ? 'checked="checked"' : '' }} value="true" /> Sales By Supplier Non PKP
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[report-agent]" {{ in_array('report-agent',$can) ? 'checked="checked"' : '' }} value="true" /> Sales By Agent
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[report-provider]" {{ in_array('report-provider',$can) ? 'checked="checked"' : '' }} value="true" /> Sales By Provider
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[report-topup-deposit-partner]" {{ in_array('report-topup-deposit-partner',$can) ? 'checked="checked"' : '' }} value="true" /> Topup Deposit Partner
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[report-inquiry-agent]" {{ in_array('report-inquiry-agent',$can) ? 'checked="checked"' : '' }} value="true" /> Inquiry Pesanan Agent
          </label><br>
           <label>
            <input type="checkbox" class="flat" name="permissions[report-rekap-sales-harian-agent]" {{ in_array('report-rekap-sales-harian-agent',$can) ? 'checked="checked"' : '' }} value="true" /> Rekap Sales Harian Agent
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[report-weekly-sales-summary]" {{ in_array('report-weekly-sales-summary',$can) ? 'checked="checked"' : '' }} value="true" /> Weekly Sales Summary
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[report-saldo-deposit-agent]" {{ in_array('report-saldo-deposit-agent',$can) ? 'checked="checked"' : '' }} value="true" /> Saldo Deposit Agent
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Users</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-user]" {{ in_array('read-user',$can) ? 'checked="checked"' : '' }} value="true" /> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-user]" {{ in_array('create-user',$can) ? 'checked="checked"' : '' }} value="true" /> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-user]" {{ in_array('update-user',$can) ? 'checked="checked"' : '' }} value="true" /> Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[reset-user]" {{ in_array('reset-user',$can) ? 'checked="checked"' : '' }} value="true" /> Reset
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[activate-user]" {{ in_array('activate-user',$can) ? 'checked="checked"' : '' }} value="true" /> Status
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Roles</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[read-role]" {{ in_array('read-role',$can) ? 'checked="checked"' : '' }} value="true" /> Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[create-role]" {{ in_array('create-role',$can) ? 'checked="checked"' : '' }} value="true" /> Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[update-role]" {{ in_array('update-role',$can) ? 'checked="checked"' : '' }} value="true" /> Update
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
