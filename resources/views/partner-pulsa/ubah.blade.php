@extends('layout.master')

@section('title')
  <title>STS | Edit Partner Pulsa</title>
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


<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Edit Partner Pulsa<small></small></h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('partner-pulsa.index') }}" class="btn btn-primary btn-sm">Back</a>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form action="" method="POST" class="form-horizontal form-label-left" novalidate>
          {{ csrf_field() }}
          <input type="hidden" name="product_id" value="">

          <div class="item form-group {{ $errors->has('partner_pulsa_code') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Partner Pulsa Code</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="name" class="form-control col-md-7 col-xs-12" name="partner_pulsa_code" type="text" value="{{ 'Code Partner' }}" readonly>
            </div>
          </div>

          <div class="item form-group {{ $errors->has('partner_pulsa_name') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="partner_pulsa_name">Partner Pulsa Name<span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="partner_pulsa_name" class="form-control" name="partner_pulsa_name" required="required" type="text" value="{{ old('partner_pulsa_name', 'Partner Cell') }}">
              @if($errors->has('partner_pulsa_name'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('partner_pulsa_name')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="flg_need_deposit">Tax</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label>
                <input type="checkbox" name="flg_need_deposit" id="flg_need_deposit" value="1" {{ old('flg_need_deposit') == 1 ? 'checked=""' : '' }}/>
              </label>
            </div>
          </div>

          <div class="item form-group {{ $errors->has('payment_termin') ? 'has-error' : ''}}" id="payment_termin" {{ old('flg_need_deposit', 1) == 0 ? 'style="display:none"' : '' }} >
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Tax Percentage <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="payment_termin" class="form-control" name="payment_termin" placeholder="E.g: 50000" required="required" type="text" value="{{ old('payment_termin', 1) }}" onkeypress="return isNumber(event)" maxlength="9">
              @if($errors->has('payment_termin'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('payment_termin')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('description') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Description <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea id="description" class="form-control" name="description" required="required">{{ old('description', 'Description about') }}</textarea>
              @if($errors->has('description'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('description')}}</span></code>
              @endif
            </div>
          </div>

          <div class="ln_solid"></div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Active</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label>
                <input type="checkbox" class="flat" name="active" checked="checked"/>
              </label>
            </div>
          </div>
          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <a href="{{ route('partner-pulsa.index') }}" class="btn btn-primary">Cancel</a>
              <button id="send" type="submit" class="btn btn-success" disabled="true">Submit</button>
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

<script>
  $(".select2_single").select2({
    placeholder: "Choose Provider",
    allowClear: true
  });

  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
    }
    return true;
  }

  $('#flg_need_deposit').click(function() {
    $("#payment_termin").toggle(this.checked);
  });
</script>
@endsection
