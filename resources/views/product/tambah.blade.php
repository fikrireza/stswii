@extends('layout.master')

@section('title')
  <title>STS | Add Product</title>
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
        <h2>Add Product<small></small></h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('product.index') }}" class="btn btn-primary btn-sm">Kembali</a>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <form action="{{ route('product.store') }}" method="POST" class="form-horizontal form-label-left" novalidate>
          {{ csrf_field() }}
          <div class="item form-group {{ $errors->has('product_code') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product Code</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="name" class="form-control col-md-7 col-xs-12" name="product_code" type="text" value="{{ $product_code }}" readonly>
            </div>
          </div>

          <div class="item form-group {{ $errors->has('product_name') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product Name <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="name" class="form-control" name="product_name" placeholder="E.g: " required="required" type="text" value="{{ old('product_name') }}">
              @if($errors->has('product_name'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('product_name')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('nominal') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nominal <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="name" class="form-control" name="nominal" placeholder="E.g: 50000" required="required" type="text" value="{{ old('nominal') }}" onkeypress="return isNumber(event)" maxlength="9">
              @if($errors->has('nominal'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('nominal')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('type_product') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Type Product <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label class="radio-inline"><input type="radio" name="type_product" {{ old('type_product') == 'PULSA' ? 'checked' : '' }} value="PULSA">PULSA</label>
              <label class="radio-inline"><input type="radio" name="type_product" {{ old('type_product') == 'DATA' ? 'checked' : '' }} value="DATA">DATA</label>
              @if($errors->has('type_product'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('type_product')}}</span></code>
              @endif
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Active</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label>
                <input type="checkbox" class="flat" name="active" />
              </label>
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <a href="{{ route('product.index') }}" class="btn btn-primary">Cancel</a>
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
</script>
@endsection
