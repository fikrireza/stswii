@extends('layout.master')

@section('title')
  <title>STS | Add Partner Product Purch Price</title>
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
    $(".alert.alert-dismissible").fadeTo(700, 0).slideUp(700, function(){
        $(this).remove();
    });
  }, 5000);
</script>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="alert {{ Session::get('alret') }} alert-dismissible fade in" role="alert">
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
        <h2>Add Partner Product Purch Price<small></small></h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('partner-product-purch-price.index') }}" class="btn btn-primary btn-sm">Back</a>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form action="{{ route('partner-product-purch-price.store') }}" method="POST" class="form-horizontal form-label-left" novalidate>
          {{ csrf_field() }}
          <div class="item form-group {{ $errors->has('partner_product_id') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
              Partner Product <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select 
                id="partner_product_id" 
                name="partner_product_id" 
                class="form-control select2_single" 
                required="required"
              >
                <option value="">Choose</option>
                @foreach ($getPartnerProduct as $key)
                <option 
                  value="{{ $key->partner_product_id }}" 
                  {{ old('partner_product_id') == $key->partner_product_id ? 'selected' : '' }}
                >
                  {{ $key->partner_product_name.'('.$key->partner_product_code.'/'.$key->partnerpulsa->partner_pulsa_name.'--'.$key->partnerpulsa->partner_pulsa_code.')' }}
                </option>
                @endforeach
              </select>
              @if($errors->has('partner_product_id'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('partner_product_id')}}</span></code>
              @endif
            </div>
          </div>
          <div class="item form-group {{ $errors->has('gross_purch_price') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
              Gross Purches Price <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input 
                id="gross_purch_price" 
                class="form-control" 
                name="gross_purch_price" 
                placeholder="E.g: 50000" 
                required="required" 
                type="text" 
                value="{{ old('gross_purch_price') }}" 
                onkeypress="return isNumber(event)" 
                maxlength="9"
              >
              @if($errors->has('gross_purch_price'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('gross_purch_price')}}</span></code>
              @endif
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
              Tax
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label>
                <input type="checkbox" name="flg_tax" id="flg_tax" value="1" {{ old('flg_tax') == 1 ? 'checked=""' : '' }}/>
              </label>
            </div>
          </div>
          <div class="item form-group {{ $errors->has('tax_percentage') ? 'has-error' : ''}}" id="tax_percentage" style="display:{{ $errors->has('tax_percentage') ? '' : 'none'}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Tax Percentage <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input 
                id="tax_percentage" 
                class="form-control" 
                name="tax_percentage" 
                placeholder="E.g: 50000" 
                required="required" 
                type="text" 
                value="{{ old('tax_percentage') }}" 
                onkeypress="return isNumber(event)" 
                maxlength="9"
              >
              @if($errors->has('tax_percentage'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('tax_percentage')}}</span></code>
              @endif
            </div>
          </div>
          <div class="item form-group {{ $errors->has('datetime_start') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">
              Date Start <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input 
                id="datetime_start" 
                name="datetime_start" 
                class="date-picker form-control col-md-7 col-xs-12" 
                required="required" 
                type="text" 
                value="{{ old('datetime_start', date('Y-m-d')) }}"
              >
              @if($errors->has('datetime_start'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('datetime_start')}}</span></code>
              @endif
            </div>
          </div>
          <div class="item form-group {{ $errors->has('datetime_end') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">
              Date End <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input 
                id="datetime_end" 
                name="datetime_end" 
                class="date-picker form-control col-md-7 col-xs-12" 
                required="required" 
                type="text" 
                value="{{ old('datetime_end', date('Y-m-d')) }}"
              >
              @if($errors->has('datetime_end'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('datetime_end')}}</span></code>
              @endif
            </div>
          </div>
          <?php  
            // <div class="ln_solid"></div>
            // <div class="item form-group">
            //   <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Active</label>
            //   <div class="col-md-6 col-sm-6 col-xs-12">
            //     <label>
            //       <input type="checkbox" class="flat" name="active" value="1" {{ old('active') == 1 ? 'checked=""' : '' }}/>
            //     </label>
            //   </div>
            // </div>
          ?>
          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <a href="{{ route('partner-product-purch-price.index') }}" class="btn btn-primary">Cancel</a>
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
<script src="{{ asset('amadeo/js/moment/moment.min.js') }}"></script>
<script src="{{ asset('amadeo/js/datepicker/daterangepicker.js') }}"></script>

@if(Session::has('tax_percentage') or old('flg_tax') == 1)
<script type="text/javascript">
$(document).ready(function(){
  $("#tax_percentage").show();
});
</script>
@endif

<script>
$(".select2_single").select2({
  placeholder: "Choose Partner Product",
  allowClear: true
});

$('#datetime_start').daterangepicker({
  singleDatePicker: true,
  calender_style: "picker_3",
  format: 'YYYY-MM-DD',
  // minDate: new Date(),
});

$('#datetime_end').daterangepicker({
  singleDatePicker: true,
  calender_style: "picker_3",
  format: 'YYYY-MM-DD',
  // minDate: new Date(),
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

</script>
@endsection
