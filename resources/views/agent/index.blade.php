@extends('layout.master')

@section('title')
  <title>STS | Agent</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
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

@can('update-provider')
<div class="modal fade modal-form-update" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('agent.update') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Update Provider</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <div class="item form-group {{ $errors->has('agent_name') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agent_name">
                Agent Name<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input
                  id="agent_name"
                  class="form-control col-md-7 col-xs-12"
                  name="agent_name"
                  type="text"
                  value="{{ old('agent_name') }}"
                >
                <input
                  id="agent_id"
                  name="agent_id"
                  type="hidden"
                  value="{{ old('agent_id') }}"
                >
                <input
                  id="version"
                  name="version"
                  type="hidden"
                  value="{{ old('version') }}"
                >
                @if($errors->has('agent_name'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('agent_name')}}</span></code>
                @endif
              </div>
            </div>

            <div class="item form-group {{ $errors->has('phone_number') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone_number">
                Phone Number <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input
                  id="phone_number"
                  class="form-control col-md-7 col-xs-12"
                  name="phone_number"
                  placeholder="Example: 0812123456789"
                  required="required"
                  type="text"
                  value="{{ old('phone_number') }}"
                >
                @if($errors->has('phone_number'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('phone_number')}}</span></code>
                @endif
              </div>
            </div>

            <div class="item form-group {{ $errors->has('address') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">
                Address <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea
                  id="address"
                  class="form-control col-md-7 col-xs-12"
                  name="address"
                  placeholder="Example: Jalan Sudirman"
                  required="required"
                >{{ old('address') }}</textarea>
                @if($errors->has('address'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('address')}}</span></code>
                @endif
              </div>
            </div>

            <div class="item form-group {{ $errors->has('city') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="city">
                City <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input
                  id="city"
                  class="form-control col-md-7 col-xs-12"
                  name="city"
                  placeholder="Example: Jakarta"
                  required="required"
                  type="text"
                  value="{{ old('city') }}"
                >
                @if($errors->has('city'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('city')}}</span></code>
                @endif
              </div>
            </div>
            <code><span style="color:red; font-size:12px;">{{ Session::get('update-false') }}</span></code>
        </div>

        <div class="modal-footer">
          <button id="send" type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endcan

<div class="page-title">
  <div class="title_left">
    <h3>All Agent <small></small></h3>
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
        <table id="producttabel" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>Name</th>
              <th>Phone</th>
              <th>Address</th>
              <th>City</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
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

<script type="text/javascript">
$(function() {
    $('#producttabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{route('agent.getDatas')}}',
        columns: [
            {data: 'agent_name'},
            {data: 'phone_number'},
            {data: 'address'},
            {data: 'city'},
            {data: 'action', orderable: false, searchable: false },
        ]
    });

    $('#producttabel').on('click', '.update', function(e) {
      var agent_id     = $(this).data('id');
      var agent_name   = $(this).data('name');
      var phone_number = $(this).data('phone');
      var address      = $(this).data('address');
      var city         = $(this).data('city');
      var version      = $(this).data('version');
      $("#agent_id").val(agent_id);
      $("#agent_name").val(agent_name);
      $("#phone_number").val(phone_number);
      $("#address").val(address);
      $("#city").val(city);
      $("#version").val(version);
  });
});
</script>

@if(Session::has('update-false'))
<script>
  $('.modal-form-update').modal('show');
</script>
@endif

@endsection
