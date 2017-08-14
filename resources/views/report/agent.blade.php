@extends('layout.master')

@section('title')
  <title>STS | Report Agent</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/pnotify/dist/pnotify.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/datepicker/datepicker3.css') }}" rel="stylesheet">
@endsection

@section('content')

@if(Session::has('hasil'))
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
      <strong>{{ Session::get('hasil') }}</strong>
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
  <div class="col-md-8 col-md-offset-2">
    <div class="x_panel">
      <div class="x_title">
        <p>Filter Agent</p>
      </div>
      <form action="" method="POST" class="form-horizontal form-label-left">
      {{ csrf_field() }}
      <div class="x_content">
        <div class="item form-group {{ $errors->has('uniqueCode') ? 'has-error' : ''}}">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product_code">Month</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <input type="text" class="form-control" name="bulan_shift" id="bulan_shift" value="" placeholder="yyyy-mm" required="">
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-6 col-md-offset-3">
            <button id="send" type="submit" class="btn btn-success">Export</button>
          </div>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>


@endsection

@section('script')
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>
<script src="{{ asset('amadeo/js/moment/moment.min.js') }}"></script>
<script src="{{asset('amadeo/vendors/datepicker/bootstrap-datepicker.js')}}"></script>

<script type="text/javascript">
$('#bulan_shift').datepicker({
    autoclose: true,
    viewMode: 'years',
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    format: "yyyy-mm",
    viewMode: "months",
    minViewMode: "months"
  });
</script>
@endsection
