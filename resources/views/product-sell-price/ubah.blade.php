@extends('layout.master')

@section('title')
  <title>STS | Add Product Sell Price</title>
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
        <h2>Add Product Sell Price<small></small></h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('product-sell-price.index') }}" class="btn btn-primary btn-sm">Back</a>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form action="{{ route('product-sell-price.store') }}" method="POST" class="form-horizontal form-label-left" novalidate>
          {{ csrf_field() }}

          {{-- <input type="hidden" name="product_sell_price_id" value="{{ $index->product_sell_price_id }}">
          <input type="hidden" name="version" value="{{ old('version', $index->version) }}"> --}}

          <div class="item form-group {{ $errors->has('product_id') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product_id">Product <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="product_id" name="product_id" class="form-control select2_single" required="required">
                <option value="">Pilih</option>
                @foreach ($product as $list)
                  <option value="{{ $list->product_id }}" {{ old('product_id', $index->product_id) == $list->product_id ? 'selected' : '' }}>{{ $list->product_name}} - {{ $list->nominal}}</option>
                @endforeach
              </select>
              @if($errors->has('product_id'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('product_id')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('gross_sell_price') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gross_sell_price">Gross Sell Price <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="gross_sell_price" class="form-control" name="gross_sell_price" placeholder="E.g: 50000" required="required" type="text" onkeypress="return isNumber(event)" maxlength="9">
              @if($errors->has('gross_sell_price'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('gross_sell_price')}}</span></code>
              @endif
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="flg_tax">Tax</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label>
                <input type="checkbox" name="flg_tax" id="flg_tax" value="Y" />
              </label>
            </div>
          </div>
          <div class="item form-group {{ $errors->has('tax_percentage') ? 'has-error' : ''}}" id="tax_percentage" {{ old('flg_tax') == 'Y' ? '' : 'style=display:none'}}>
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tax_percentage">Tax Percentage <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="tax_percentage" class="form-control" name="tax_percentage" placeholder="E.g: 10" required="required" type="text" onkeypress="return isNumber(event)" maxlength="9">
              @if($errors->has('tax_percentage'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('tax_percentage')}}</span></code>
              @endif
            </div>
          </div>
          <div class="item form-group {{ $errors->has('datetime_start') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="datetime_start">Date Start <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="datetime_start" name="datetime_start" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" >
              @if($errors->has('datetime_start'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('datetime_start')}}</span></code>
              @endif
            </div>
          </div>
          <input id="datetime_end" name="datetime_end" class="date-picker form-control col-md-7 col-xs-12" required="required" type="hidden" value="{{ old('datetime_end', date('Y-m-d H:i:s', strtotime($index->datetime_end))) }}">
          {{-- <div class="item form-group {{ $errors->has('datetime_end') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="datetime_end">Date End <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              @if($errors->has('datetime_end'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('datetime_end')}}</span></code>
              @endif
            </div>
          </div> --}}
          <div class="ln_solid"></div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="active">Active</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label>
                <input id="active" type="checkbox" class="flat" name="active" value="Y" />
              </label>
            </div>
          </div>
          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <a href="{{ route('product-sell-price.index') }}" class="btn btn-primary">Cancel</a>
              @can('update-product-sell-price')
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
<script src="{{ asset('amadeo/js/moment/moment.min.js') }}"></script>
<script src="{{ asset('amadeo/js/datepicker/daterangepicker.js') }}"></script>

<script>
  $(".select2_single").select2({
    placeholder: "Choose Product",
    allowClear: true
  });

  $('#datetime_start').daterangepicker({
    "calender_style": "picker_3",
    "singleDatePicker": true,
    "format": 'YYYY-MM-DD H:m:s',
    "showDropdowns": true,
    "timePicker": true,
    "timePicker24Hour": true,
    "timePickerSeconds": true,
    "timePickerIncrement": 1,
    "minDate": new Date(),
  });

  $('#datetime_end').daterangepicker({
    "calender_style": "picker_3",
    "singleDatePicker": true,
    "format": 'YYYY-MM-DD H:m:s',
    "showDropdowns": true,
    "timePicker": true,
    "timePicker24Hour": true,
    "timePickerSeconds": true,
    "timePickerIncrement": 1,
    "minDate": new Date(),
  });

  $('#flg_tax').click(function() {
    $("#tax_percentage").toggle(this.checked);
  });

  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
    }
    return true;
  }

  $('select[name="product_id"]').on('change', function() {
    var product_id = $(this).val();
    if(product_id) {
        $.ajax({
            url: '{{ url('/') }}/product-sell-price/product/'+product_id,
            type: "GET",
            dataType: "json",

            success:function(data) {
              var nominal = data.nominal
              var nominal = parseInt(nominal).toLocaleString(
                  undefined,
                  {
                    minimumFractionDigits: 2
                  }
                );

              $('input[type="text"]#nominal').attr('value', 'Rp. '+nominal);

            }
        });
    }else{
        $('input[type="text"]#nominal').empty();
    }
  });
</script>
@endsection
