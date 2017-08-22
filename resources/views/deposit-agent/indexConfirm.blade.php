@extends('layout.master')

@section('title')
  <title>STS | Deposit Agent</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/pnotify/dist/pnotify.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
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

@can('confirm-deposit-confirm')
<div class="modal fade modal-form-confirm" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('deposit-agent.confirm') }}" method="POST" class="form-horizontal form-label-left">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title" id="myModalLabel2">Confirm Deposit Agent</h4>
        </div>
        <div class="modal-body">
          {{ csrf_field() }}
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Client Id</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="confirm_clientId" class="form-control col-md-7 col-xs-12" name="clientId" type="text" value="" readonly="">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Name</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="confirm_name" class="form-control col-md-7 col-xs-12" name="name" type="text" value="" readonly="">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Unique Code</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="confirm_uniqueCode" class="form-control col-md-7 col-xs-12" name="uniqueCode" type="text" value="" readonly="">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Unique Code Date</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="confirm_uniqueCodeDate" class="form-control col-md-7 col-xs-12" name="uniqueCodeDate" type="text" value="" readonly="">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Amount <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input class="form-control col-md-7 col-xs-12" name="amount" type="text" value="" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required="">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button id="send" type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endcan

<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="x_panel">
      <div class="x_title">
        <p>Check Unique Code</p>
      </div>
      <form action="{{ route('deposit-agent-confirm.getUniqueCode')}}" method="POST" class="form-horizontal form-label-left">
      {{ csrf_field() }}
      <div class="x_content">
        <div class="item form-group {{ $errors->has('uniqueCode') ? 'has-error' : ''}}">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="product_code">Unique Code</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            @if (isset($prosesUniqueCode))
              <input class="form-control col-md-7 col-xs-12" name="uniqueCode" type="text" value="{{ old('uniqueCode', $uniqueCode) }}">
            @else
              <input class="form-control col-md-7 col-xs-12" name="uniqueCode" type="text" value="{{ old('uniqueCode') }}">
            @endif
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-6 col-md-offset-3">
            <button id="send" type="submit" class="btn btn-success">Check</button>
          </div>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>

@if (isset($prosesUniqueCode))
  <div class="page-title">
    <div class="title_left">
      <h3>Agent List <small></small></h3>
    </div>
  </div>

  <div class="clearfix"></div>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Agent </h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content table-responsive">
          <table id="deposit-agent" class="table table-striped table-bordered no-footer" width="100%">
            <thead>
              <tr role="row">
                <th>No</th>
                <th>Client ID</th>
                <th>Name</th>
                <th>Unique Code</th>
                <th>Unique Code Date</th>
                @can('confirm-deposit-confirm')
                <th>Action</th>
                @endcan
              </tr>
            </thead>
            <tbody>
              @php $count=1; @endphp
              @foreach ($prosesUniqueCode->uniqueCodeList as $list)
              <tr>
                <td>{{ $count++ }}</td>
                <td>{{ $list->clientId }}</td>
                <td>{{ $list->name }}</td>
                <td>{{ $list->uniqueCode }}</td>
                <td>{{ $list->uniqueCodeDate }}</td>
                @can('confirm-deposit-confirm')
                <td><a class="confirm" data-name="{{$list->name}}" data-clientid="{{$list->clientId}}" data-uniquecode="{{$list->uniqueCode}}" data-uniquecodedate="{{$list->uniqueCodeDate}}" data-toggle='modal' data-target='.modal-form-confirm'><span class='btn btn-xs btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='Confirm'>Confirm</span></a></td>
                @endcan
              @endforeach
            </tbody>
          </table>
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
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>
<script src="{{ asset('amadeo/js/formatNumber.js') }}"></script>


<script type="text/javascript">
  $('#deposit-agent').DataTable();

  @can('confirm-deposit-confirm')
  $(function(){
    $(document).on('click', '.confirm', function(e) {
      var clientId        = $(this).data('clientid');
      var name            = $(this).data('name');
      var uniqueCode      = $(this).data('uniquecode');
      var uniqueCodeDate  = $(this).data('uniquecodedate');
      $("#confirm_clientId").val(clientId);
      $("#confirm_name").val(name);
      $("#confirm_uniqueCode").val(uniqueCode);
      $("#confirm_uniqueCodeDate").val(uniqueCodeDate);
    });
  });
  @endcan

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
