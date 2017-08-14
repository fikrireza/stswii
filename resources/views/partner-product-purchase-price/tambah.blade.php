@extends('layout.master')

@section('title')
  <title>STS | Add Partner Product Purchase Price</title>
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
        <h2>Add Partner Product Purchase Price<small></small></h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('partner-product-purch-price.index') }}" class="btn btn-primary btn-sm">Kembali</a>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form action="{{ route('partner-product-purch-price.store') }}" method="POST" class="form-horizontal form-label-left" novalidate>
          {{ csrf_field() }}

          <div class="item form-group {{ $errors->has('partner_product_id') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="partner_product_id">Partner Product <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="partner_product_id" name="partner_product_id" class="form-control select2_single" required="required">
                <option value="">Pilih</option>
                @foreach ($partnerProduct as $list)
                  <option value="{{ $list->partner_product_id }}" {{ old('partner_product_id') == $list->partner_product_id ? 'selected' : '' }}>{{ $list->partner_product_name}} - {{ $list->partner_product_code}}</option>
                @endforeach
              </select>
              @if($errors->has('partner_product_id'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('partner_product_id')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('gross_purch_price') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="gross_purch_price">Gross Sell Price <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="gross_purch_price" class="form-control" name="gross_purch_price" placeholder="E.g: 50000" required="required" type="text" value="{{ old('gross_purch_price') }}" onkeypress="return isNumber(event)" maxlength="9">
              @if($errors->has('gross_purch_price'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('gross_purch_price')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="flg_tax">Tax</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label>
                <input type="checkbox" name="flg_tax" id="flg_tax" value="Y" {{ old('flg_tax') == "Y" ? 'checked' : '' }}/>
              </label>
            </div>
          </div>

          <div class="item form-group {{ $errors->has('tax_percentage') ? 'has-error' : ''}}" id="tax_percentage" {{ old('flg_tax') == 'Y' ? '' : 'style=display:none'}}>
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tax_percentage">Tax Percentage <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="tax_percentage" class="form-control" name="tax_percentage" placeholder="E.g: 10" required="required" type="text" value="{{ old('tax_percentage') }}" onkeypress="return isNumber(event)" maxlength="9">
              @if($errors->has('tax_percentage'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('tax_percentage')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('datetime_start') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="datetime_start">Date Start <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="datetime_start" name="datetime_start" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="{{ old('datetime_start', date('Y-m-d H:i:s')) }}">
              @if($errors->has('datetime_start'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('datetime_start')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('datetime_end') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="datetime_end">Date End <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="datetime_end" name="datetime_end" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="{{ old('datetime_end', date('Y-m-d H:i:s')) }}">
              @if($errors->has('datetime_end'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('datetime_end')}}</span></code>
              @endif
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="active">Active</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label>
                <input id="active" type="checkbox" class="flat" name="active" value="Y" {{ old('active') == "Y" ? 'checked' : '' }}/>
              </label>
            </div>
          </div>

          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <a href="{{ route('partner-product-purch-price.index') }}" class="btn btn-primary">Cancel</a>
              @can('create-partner-product-purch-price')
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
<script src="http://trentrichardson.com/examples/timepicker/jquery-ui-timepicker-addon.js"></script>

<script>
  $(".select2_single").select2({
    placeholder: "Choose Partner Product",
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

$(document).ready(function() {
$('select[name="partner_product_id"]').on('change', function() {
  var partner_product_id = $(this).val();
  if(partner_product_id) {
      $.ajax({
          url: '{{ url('/') }}/partner-product-purch-price/product/'+partner_product_id,
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
});
</script>
@endsection
