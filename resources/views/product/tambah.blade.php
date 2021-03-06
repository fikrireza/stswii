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

@if(Session::has('gagal'))
<script>
  window.setTimeout(function() {
    $(".alert-danger").fadeTo(700, 0).slideUp(700, function(){
        $(this).remove();
    });
  }, 15000);
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


<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Add Product<small></small></h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('product.index') }}" class="btn btn-primary btn-sm">Back</a>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <form action="{{ route('product.store') }}" method="POST" class="form-horizontal form-label-left" novalidate>
          {{ csrf_field() }}
          <div class="item form-group {{ $errors->has('provider_id') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="provider_id">Provider</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select class="form-control col-md-7 col-xs-12 select2_single" name="provider_id" id="provider_id">
                <option value="">Select Provider</option>
                @foreach($provider as $list)
                <option value="{{$list->provider_id}}" @if(old('provider_id') == $list->provider_id) selected @endif>{{$list->provider_name}}</option>
                @endforeach
              </select>
              @if($errors->has('provider_id'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('provider_id')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('product_code') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product_code">Product Code</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="product_code" class="form-control col-md-7 col-xs-12" name="product_code" type="text" value="{{ old('product_code') }}" placeholder="E.g: " onchange="this.value = this.value.toUpperCase()">
              @if($errors->has('product_code'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('product_code')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('product_name') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product_name">Product Name <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="product_name" class="form-control" name="product_name" placeholder="E.g: " required="required" type="text" value="{{ old('product_name') }}" onchange="this.value = this.value.toUpperCase()">
              @if($errors->has('product_name'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('product_name')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('nominal') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nominal">Nominal <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="nominal" class="form-control" name="nominal" placeholder="E.g: 50000" required="required" type="text" value="{{ old('nominal') }}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" maxlength="9">
              @if($errors->has('nominal'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('nominal')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('type') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Type Product <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label class="radio-inline"><input type="radio" name="type" {{ old('type') == 'PULSA' ? 'checked' : '' }} value="PULSA">PULSA</label>
              <label class="radio-inline"><input type="radio" name="type" {{ old('type') == 'DATA' ? 'checked' : '' }} value="DATA">DATA</label>
              @if($errors->has('type'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('type')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('sort_number') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sort_number">Sort Number <span class="required">*</span></label>
            <div class="col-md-2 col-sm-6 col-xs-12">
              <input id="sort_number" class="form-control" name="sort_number" required="required" type="number" value="1" min="1">
              @if($errors->has('sort_number'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('sort_number')}}</span></code>
              @endif
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="active">Active</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label>
                <input type="checkbox" class="flat" name="active" id="active" value="Y" {{old('active') == 'Y' ? 'checked' : ''}}/>
              </label>
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <a href="{{ route('product.index') }}" class="btn btn-primary">Cancel</a>
              @can('create-product')
              <button id="send" type="submit" class="btn btn-success">Submit</button>
              @endcan
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
<script src="{{ asset('amadeo/js/formatNumber.js') }}"></script>


<script>
  $(".select2_single").select2({
    placeholder: "Choose Provider",
    allowClear: true
  });
</script>
@endsection
