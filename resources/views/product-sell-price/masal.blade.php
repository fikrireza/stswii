@extends('layout.master')

@section('title')
  <title>STS | Upload Product Sell Price</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/pnotify/dist/pnotify.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
<style type="text/css">
  /*td .label-hide{
    display:  inline-block !important;
    visibility: visible;
    opacity: 1;
    transition: all .51s;
  }*/
  td .label-hide{
    display:  block !important;
    /*display:  none !important;*/
    visibility: visible;
    /*visibility: hidden;*/
    opacity: 1;
    /*opacity: 0;*/
  }
  /*td .input-hide{
    display:  none !important;
    visibility: hidden;
    opacity: 0;
    transition: all .51s;
  }*/
  td .input-hide{
    display:  inline-block !important;
    visibility: visible;
    opacity: 1;
  }
</style>
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
    <h3>Upload Product Sell Price <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Product Sell Price </h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('product-sell-price.template') }}" class="btn btn-primary btn-sm"> Download Template</a>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form class="form-horizontal form-label-left" action="{{ route('product-sell-price.prosesTemplate') }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="item form-group {{ $errors->has('file') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">File Upload <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="file" name="file" value="" accept=".xls, .xlsx">
              @if($errors->has('file'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('file')}}</span></code>
              @endif
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <a href="{{ route('product-sell-price.index') }}" class="btn btn-primary">Cancel</a>
              <button id="send" type="submit" class="btn btn-success">Process</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Check</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <form class="form-horizontal form-label-left" action="{{ route('product-sell-price.storeTemplate') }}" method="post">
        {{ csrf_field() }}
        <table class="table table-striped table-bordered no-footer tablecheck" width="100%" id="">
          <thead>
            <th>Selection</th>
            <th>Provider</th>
            <th>Gross Sell Price</th>
            <th>Tax Percentage</th>
            <th>Datetime Start</th>
            <th>Datetime End</th>
          </thead>

          <tbody>
            @php
              $urut = 0;
              $arrProvNameSelect = array('', 'Telkomsel', 'Indosat', 'Ooredo', '3');
              $arrProvName = array(
                '', 
                'Telkomsel', 'Telkomsel', 'Telkomsel', 'Telkomsel', 'Telkomsel', 
                'Indosat', 'Indosat', 'Indosat', 'Indosat', 'Indosat', 
                'Ooredo', 'Ooredo', 'Ooredo', 'Ooredo', 'Ooredo', 
                '3', '3', '3', '3', '3'
              );
              $arrPriceSell = array(
                '', 
                '5000', '10000', '20000', '50000', '100000', 
                '5000', '10000', '20000', '50000', '100000', 
                '5000', '10000', '20000', '50000', '100000', 
                '5000', '10000', '20000', '50000', '100000'
              );
              $arrTaxPerc = array(
                '', 
                '0', '0', '0', '0', '0', 
                '0', '0', '0', '0', '0', 
                '0', '0', '0', '0', '0', 
                '0', '0', '0', '0', '0'
              );
              $arrStartDate = array(
                '', 
                '01/01/2017 00:00:00', '01/01/2017 00:00:00', '01/01/2017 00:00:00', '01/01/2017 00:00:00', '01/01/2017 00:00:00', 
                '01/01/2017 00:00:00', '01/01/2017 00:00:00', '01/01/2017 00:00:00', '01/01/2017 00:00:00', '01/01/2017 00:00:00', 
                '01/01/2017 00:00:00', '01/01/2017 00:00:00', '01/01/2017 00:00:00', '01/01/2017 00:00:00', '01/01/2017 00:00:00', 
                '01/01/2017 00:00:00', '01/01/2017 00:00:00', '01/01/2017 00:00:00', '01/01/2017 00:00:00', '01/01/2017 00:00:00'
              );
              $arrEndDate = array(
                '', 
                '31/12/2017 00:00:00', '31/12/2017 00:00:00', '31/12/2017 00:00:00', '31/12/2017 00:00:00', '31/12/2017 00:00:00', 
                '31/12/2017 00:00:00', '31/12/2017 00:00:00', '31/12/2017 00:00:00', '31/12/2017 00:00:00', '31/12/2017 00:00:00', 
                '31/12/2017 00:00:00', '31/12/2017 00:00:00', '31/12/2017 00:00:00', '31/12/2017 00:00:00', '31/12/2017 00:00:00', 
                '31/12/2017 00:00:00', '31/12/2017 00:00:00', '31/12/2017 00:00:00', '31/12/2017 00:00:00', '31/12/2017 00:00:00', 
              );
            @endphp
            @for ($i=1; $i < 20 ; $i++)
            <tr>
              <td>
                <input type="button" name="delete" value="x" class="btn btn-danger btn-sm" onclick="DeleteRowFunction(this);">
              </td>
              <td>
                <span class="label-hide">{{ $arrProvName[$i] }}</span>
                <select id="product_id" name="product_id[{{$urut}}]" class="form-control select2_single input-hide input-text" required="required">
                  <option value="">Pilih</option>
                  @for($p=1; $p<=4; $p++)
                  <option 
                    value="{{ $arrProvNameSelect[$p] }}" 
                    {{ $arrProvName[$i] == $arrProvNameSelect[$p] ? 'selected' : '' }}
                  >
                    {{ $arrProvNameSelect[$p] }}
                  </option>
                  @endfor
                </select>
              </td>
              <td>
                <span class="label-hide">Rp. {{ number_format($arrPriceSell[$i],0) }}</span>
                <input type="text" name="gross_sell_price[{{$urut}}]" class="form-control input-hide input-text currency" value="{{ $arrPriceSell[$i] }}" onkeypress="return isNumber(event)">
              </td>
              <td>
                <span class="label-hide">{{ $arrTaxPerc[$i] }}</span>
                <input type="text" name="tax_percentage[{{$urut}}]" class="form-control input-hide input-text percentage" value="{{ $arrTaxPerc[$i] }}" onkeypress="return isNumber(event) "/>
              </td>
              <td>
                <span class="label-hide">{{ $arrStartDate[$i] }}</span>
                <input type="text" name="datetime_start[{{$urut}}]" class="datetime_start form-control input-hide input-text" value="{{ $arrStartDate[$i] }}" />
              </td>
              <td>
                <span class="label-hide">{{ $arrEndDate[$i] }}</span>
                <input type="text" name="datetime_end[{{$urut}}]" class="datetime_end form-control input-hide input-text" value="{{ $arrEndDate[$i] }}" />
              </td>

            </tr>
            @php
              $urut++
            @endphp
            @endfor
          </tbody>
          
        </table>
        <button type="submit" name="button" class="btn btn-success btn-bg">Upload</button>
        </form>
      </div>
    </div>
  </div>
</div>


@endsection

@section('script')
<script src="{{ asset('amadeo/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-scroller/js/datatables.scroller.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('amadeo/vendors/iCheck/icheck.min.js')}}"></script>
<script src="{{ asset('amadeo/vendors/switchery/dist/switchery.min.js')}}"></script>
<script src="{{ asset('amadeo/js/moment/moment.min.js') }}"></script>
<script src="{{ asset('amadeo/js/datepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>

<script type="text/javascript">
  // $(document).on('load', '.input-text', function(e) {
  //   var lebel = $(this).val();
  //   if($(this).hasClass("currency")){
  //     var lebel = $(this).val();
  //   }
  //   if($(this).hasClass("percentage")){
  //     lebel = lebel + '%';
  //   }
  //   $(this).prev().html(lebel);
  // });

  $(document).on('change', '.input-text', function(e) {
    var lebel = $(this).val();
    if($(this).hasClass("currency")){
      var lebel = 'Rp. ' + currency($(this).val());
    }
    if($(this).hasClass("percentage")){
      lebel = lebel + '%';
    }
    $(this).prev().html(lebel);
  });

  function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
  }

  function DeleteRowFunction(btndel) {
    if (typeof(btndel) == "object") {
      $(btndel).closest("tr").remove();
    } else {
      return false;
    }
  }

  function currency(number)
  {
    var format = '';    
    var angkarev = number.toString().split('').reverse().join('');
    for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) format += angkarev.substr(i,3)+',';
    return format.split('',format.length-1).reverse().join('');
  }

  $('.datetime_start').daterangepicker({
    singleDatePicker: true,
    calender_style: "picker_2",
    format: 'YYYY-MM-DD hh:mm:ss',
    timePicker: true,
    // minDate: new Date(),
  });
  $('.datetime_end').daterangepicker({
    singleDatePicker: true,
    calender_style: "picker_2",
    format: 'YYYY-MM-DD hh:mm:ss',
    timePicker: true,
    // minDate: new Date(),
  });

  $('.tablecheck').DataTable();

  // $(function() {
  //   $('input.input-hide').change(function(event) {
  //     console.log($(this).val());
  //   });
  // });
</script>


@endsection
