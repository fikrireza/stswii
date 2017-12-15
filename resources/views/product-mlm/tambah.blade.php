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
        <form action="{{ route('product-mlm.store') }}" method="POST" class="form-horizontal form-label-left" novalidate>
          {{ csrf_field() }}

          <div class="item form-group {{ $errors->has('product_id') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product_id">Product</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select class="form-control col-md-7 col-xs-12 select2_single" name="product_id" id="product_id">
                <option value="">Select Product</option>
                @foreach($product as $list)
                <option value="{{$list->product_id}}" @if(old('product_id') == $list->product_id) selected @endif>{{$list->product_name}}</option>
                @endforeach
              </select>
              @if($errors->has('product_id'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('product_id')}}</span></code>
              @endif
            </div>
          </div>

          </div>

          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <a href="{{ route('product-mlm.index') }}" class="btn btn-primary">Cancel</a>
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
    placeholder: "Choose Product",
    allowClear: true
  });
</script>
@endsection
