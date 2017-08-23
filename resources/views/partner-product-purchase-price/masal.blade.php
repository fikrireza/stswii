@extends('layout.master')

@section('title')
  <title>STS | Upload Supplier Product Purchase Price</title>
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
    <h3>Supplier Product Purchase Price <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Supplier Product Purchase Price </h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('partner-product-purch-price.index') }}" class="btn btn-primary btn-sm">Back</a>
          {{-- <a href="{{ route('partner-product-purch-price.template') }}" class="btn btn-primary btn-sm"> Download Template</a> --}}
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form class="form-horizontal form-label-left" action="{{ route('partner-product-purch-price.template') }}" method="post">
          {{ csrf_field() }}
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <h6>Download template before upload mass</h6>
            </div>
          </div>
          <div class="item form-group {{ $errors->has('partner_pulsa_id') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Supplier <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="partner_pulsa_id" name="partner_pulsa_id" class="form-control select2_single" required="required">
                <option value="">Pilih</option>
                @foreach ($getPartner as $list)
                  <option value="{{ $list->partner_pulsa_id }}" {{ old('partner_pulsa_id') == $list->partner_pulsa_id ? 'selected' : '' }}>{{ $list->partner_pulsa_code }} - {{ $list->partner_pulsa_name}}</option>
                @endforeach
              </select>
              @if($errors->has('file'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('file')}}</span></code>
              @endif
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <button id="send" type="submit" class="btn btn-primary btn-sm">Download Template</button>
            </div>
          </div>
        </form>

        <div class="ln_solid"></div>

        <form class="form-horizontal form-label-left" action="{{ route('partner-product-purch-price.prosesTemplate') }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <h6>Upload file template to system</h6>
            </div>
          </div>
          <div class="item form-group {{ $errors->has('partner_pulsa_id') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="partner_pulsa_id">Supplier <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="partner_pulsa_id" name="partner_pulsa_id" class="form-control select2_single" required="required">
                <option value="">Pilih</option>
                @foreach ($getPartner as $list)
                  <option value="{{ $list->partner_pulsa_id }}" {{ old('partner_pulsa_id') == $list->partner_pulsa_id ? 'selected' : '' }}>{{ $list->partner_pulsa_code }} - {{ $list->partner_pulsa_name}}</option>
                @endforeach
              </select>
              @if($errors->has('file'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('file')}}</span></code>
              @endif
            </div>
          </div>
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
              <button id="send" type="submit" class="btn btn-success">Upload</button>
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
        <h2>Check - {{ $getName->partner_pulsa_name }}</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <form class="form-horizontal form-label-left" action="{{ route('partner-product-purch-price.storeTemplate') }}" method="post">
        <input type="hidden" name="partner_pulsa_id" value="{{ $getName->partner_pulsa_id }}">
        <table class="table table-striped table-bordered no-footer tablecheck" width="100%">
          <thead>
            <th>No</th>
            <th>Supplier Product Code</th>
            <th>Gross Purchase Price</th>
            <th>Tax Percentage</th>
            <th>Datetime Start</th>
            <th>Datetime End</th>
            <th>Active</th>
            <th>Delete</th>
          </thead>
          {{ csrf_field() }}
          <tbody>
            @php
              $urut = 0;
            @endphp
            @foreach ($collect as $key)
            <tr>
              <td>
                {{ $urut + 1 }}
              </td>
              <td>
                {{$key['partner_product_code']}}
                <input type="hidden" name="partner_product_code[{{$urut}}]" value="{{$key['partner_product_code']}}">
              </td>
              <td>
                Rp. {{ number_format($key['gross_purch_price']) }}
                <input type="hidden" name="gross_purch_price[{{$urut}}]" value="{{ $key['gross_purch_price'] }}"/>
              </td>
              <td>
                {{ $key['tax_percentage'] }} %
                <input type="hidden" name="tax_percentage[{{$urut}}]" value="{{ $key['tax_percentage'] }}"/>
              </td>
              <td>
                {{ $key['datetime_start'] }}
                <input type="hidden" name="datetime_start[{{$urut}}]" value="{{ $key['datetime_start'] }}"/>
              </td>
              <td>
                {{ $key['datetime_end'] }}
                <input type="hidden" name="datetime_end[{{$urut}}]" value="{{ $key['datetime_end'] }}"/>
              </td>
              <td>
                {{ $key['active'] }}
                <input type="hidden" name="active[{{$urut}}]" value="{{ $key['active'] }}"/>
              </td>
              <td>
                <input type="button" name="delete" value="x" class="btn btn-danger btn-sm btn-delete">
              </td>
            </tr>
            @php
              $urut++
            @endphp
            @endforeach
          </tbody>
        </table>
        <button type="submit" name="button" class="btn btn-success btn-bg btn-submit" onclick="return false">Process</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endif

@if (isset($error))
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2 style="color: red;">Data Error</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form class="form-horizontal form-label-left" action="{{ route('product-sell-price.storeTemplate') }}" method="post">
        {{ csrf_field() }}
        <table class="table table-striped table-bordered no-footer tablecheck" width="100%" id="">
          <thead>
            <th>Row</th>
            <th>Supplier Product Code</th>
            <th>Gross Purchase Price</th>
            <th>Tax Percentage</th>
            <th>Datetime Start</th>
            <th>Datetime End</th>
            <th>Active</th>
            <th>Message</th>
          </thead>

          <tbody>
            @foreach ($error as $key)
            <tr>
              <td>
                {{$key['row'] + 1}}
              </td>
              <td>
                {{$key['partner_product_code']}}
              </td>
              <td>
                Rp. {{ number_format($key['gross_purch_price']) }}
              </td>
              <td>
                {{ $key['tax_percentage'] }} %
              </td>
              <td>
                {{ $key['datetime_start'] }}
              </td>
              <td>
                {{ $key['datetime_end'] }}
              </td>
              <td>
                {{ $key['active'] }}
              </td>
              <td>
                {!! $key['message'] !!}
              </td>
            </tr>
            @endforeach
          </tbody>

        </table>
        </form>
      </div>
    </div>
  </div>
</div>
@endif

@if (isset($pass) && $pass != '')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2 style="color: green">Successfully saved</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form class="form-horizontal form-label-left" action="{{ route('product-sell-price.storeTemplate') }}" method="post">
        {{ csrf_field() }}
        <table class="table table-striped table-bordered no-footer tablecheck" width="100%" id="">
          <thead>
            <th>No</th>
            <th>Supplier Product Code</th>
            <th>Gross Purchase Price</th>
            <th>Tax Percentage</th>
            <th>Datetime Start</th>
            <th>Datetime End</th>
            <th>Active</th>
          </thead>

          <tbody>
            @foreach ($pass as $key)
            <tr>
              <td>
                {{$key['row'] + 1}}
              </td>
              <td>
                {{$key['partner_product_code']}}
              </td>
              <td>
                Rp. {{ number_format($key['gross_purch_price']) }}
              </td>
              <td>
                {{ $key['tax_percentage'] }} %
              </td>
              <td>
                {{ $key['datetime_start'] }}
              </td>
              <td>
                {{ $key['datetime_end'] }}
              </td>
              <td>
                {{ $key['active'] }}
              </td>
            </tr>
            @endforeach
          </tbody>

        </table>
        </form>
      </div>
    </div>
  </div>
</div>
@endif

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
  $(".select2_single").select2({
    placeholder: "Choose Supplier",
    allowClear: true
  });

  var table = $('.tablecheck').DataTable({
    "aoColumnDefs": [
      { "bSearchable": false, "aTargets": [ 0, 1 ] }
    ]
  });

  $('.tablecheck').on( 'click', '.btn-delete', function () {
      table
          .row( $(this).parents('tr') )
          .remove()
          .draw();
  } );

  $('.btn-submit').click( function() {
        var data = table.$('input, select').serialize();
        window.location = "{{ route('partner-product-purch-price.storeTemplate') }}?"+data;
    } );
</script>
@endsection
