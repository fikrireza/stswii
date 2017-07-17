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

@if (isset($collect))
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Check</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <th>Select All <br><input type="checkbox" onchange="checkAll(this)" name="chk[]" /></th>
            <th>Provider</th>
            <th>Gross Sell Price</th>
            <th>Tax Percentage</th>
            <th>Datetime Start</th>
            <th>Datetime End</th>
            <th>Active</th>
            <th>Version</th>
          </thead>
          <form class="form-horizontal form-label-left" action="{{ route('product-sell-price.storeTemplate') }}" method="post">
          {{ csrf_field() }}
          <tbody>
            @php
              $urut = 0;
            @endphp
            @foreach ($collect as $key)
            <tr>
              <td><input type="checkbox" name="test[check][{{$urut}}]" value="1"/></td>
              <td><select id="product_id" name="test[product_id][{{$urut}}]" class="form-control select2_single" required="required">
                    <option value="">Pilih</option>
                    @foreach ($getProduct as $product)
                      <option value="{{ $product->id }}" {{ old('product_id', $key['product_id']) == $product->id ? 'selected' : '' }}>{{ $product->product_name}}</option>
                    @endforeach
                  </select></td>
              <td><input type="text" name="test[gross_sell_price][{{$urut}}]" class="form-control" value="{{ $key['gross_sell_price'] }}"></td>
              <td><input type="text" name="test[tax_percentage][{{$urut}}]" class="form-control" value="{{ $key['tax_percentage'] }}" /></td>
              <td><input type="text" name="test[datetime_start][{{$urut}}]" id="datetime_start" class="date-picker form-control" value="{{ $key['datetime_start'] }}" /></td>
              <td><input type="text" name"test[datetime_end]" id="datetime_end" class="date-picker form-control" value="{{ $key['datetime_end'] }}" /></td>
              <td><input type="checkbox" class="flat form-control" name="test[active][{{$urut}}]" value="1" {{ old('active', $key['active']) == 1 ? 'checked=""' : '' }}/></td>
              <td><input type="number" class="form-control" name="test[version][{{$urut}}]" value="{{ $key['version'] }}" /></td>
            </tr>
            @php
              $urut++
            @endphp
            @endforeach
            <tr>
              <td colspan="8"><button type="submit" name="button" class="btn btn-success btn-bg">Upload</button></td>
            </tr>
          </tbody>
          </form>
        </table>
      </div>
    </div>
  </div>
</div>
@endif

@endsection

@section('script')
<script src="{{ asset('amadeo/vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('amadeo/vendors/iCheck/icheck.min.js')}}"></script>
<script src="{{ asset('amadeo/vendors/switchery/dist/switchery.min.js')}}"></script>
<script src="{{ asset('amadeo/js/moment/moment.min.js') }}"></script>
<script src="{{ asset('amadeo/js/datepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>

<script type="text/javascript">
  function checkAll(ele) {
   var checkboxes = document.getElementsByTagName('input');
   if (ele.checked) {
       for (var i = 0; i < checkboxes.length; i++) {
           if (checkboxes[i].type == 'checkbox') {
               checkboxes[i].checked = true;
           }
       }
   } else {
       for (var i = 0; i < checkboxes.length; i++) {
           console.log(i)
           if (checkboxes[i].type == 'checkbox') {
               checkboxes[i].checked = false;
           }
       }
   }
 }

  $('#datetime_start').daterangepicker({
    singleDatePicker: true,
    calender_style: "picker_3",
    format: 'YYYY-MM-DD H:m:s',
    minDate: new Date(),
  });
  $('#datetime_end').daterangepicker({
    singleDatePicker: true,
    calender_style: "picker_3",
    format: 'YYYY-MM-DD H:m:s',
    minDate: new Date(),
  });
</script>
@endsection
