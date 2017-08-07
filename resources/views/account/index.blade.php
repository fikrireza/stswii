@extends('layout.master')

@section('title')
  <title>STS | Account</title>
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

@can('activate-user')
<div class="modal fade modal-inactive" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Nonactive Account</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setInActive">Ya</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade modal-active" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Activated Account</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setActive">Ya</a>
      </div>
    </div>
  </div>
</div>
@endcan

@can('reset-user')
<div class="modal fade modal-reset" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Reset Password Account</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setReset">Ya</a>
      </div>

    </div>
  </div>
</div>
@endcan

<div class="page-title">
  <div class="title_left">
    <h3>All Account <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Account </h2>
        <ul class="nav panel_toolbox">
          @can('create-user')
          <a href="{{ route('account.tambah') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
          @endcan
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table id="producttabel" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Username</th>
              <th>Email</th>
              <th>Avatar</th>
              <th>Role</th>
              @can('activate-user')
              <th>Status</th>
              @endcan
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php
              $no = 1;
            @endphp
            @foreach ($getUser as $key)
            <tr>
              <td>{{ $no++ }}</td>
              <td>{{ $key->name }}</td>
              <td>{{ $key->email }}</td>
              <td>{{ $key->avatar }}</td>
              <td>@foreach($key->roles as $role)
                  {{ $role->name }}
                  @endforeach
              </td>
              @can('activate-user')
              <td>@if($key->confirmed == 1)
                    <a href="" class="inactive" data-value="{{ $key->id }}" data-toggle="modal" data-target=".modal-inactive"><span class="label label-success" data-toggle="tooltip" data-placement="top" title="Active">Active</span></a>
                  @else
                    <a href="" class="active" data-value="{{ $key->id }}" data-toggle="modal" data-target=".modal-active"><span class="label label-danger" data-toggle="tooltip" data-placement="top" title="NonActive">Not Active</span></a>
                  @endif
              </td>
              @endcan
              <td>
                @can('update-user')
                <a href="{{ route('account.ubah', $key->id) }}" class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-pencil"></i></a>
                @endcan
                @can('reset-user')
                <a href="" class="reset" data-value="{{ $key->id }}" data-toggle="modal" data-target=".modal-reset"><span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Reset"><i class="fa fa-recycle"></i></span></a>
                @endcan
              </td>
            </tr>
            @endforeach
          </tbody>
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
  $('#producttabel').DataTable();

  $(function(){
    @can('reset-user')
    $(document).on('click','a.reset', function(){
      var a = $(this).data('value');
      $('#setReset').attr('href', "{{ url('/') }}/account/reset/"+a);
    });
    @endcan

    @can('activate-user')
    $(document).on('click', 'a.active', function(){
      var a = $(this).data('value');
      $('#setActive').attr('href', "{{ url('/') }}/account/actived/"+a);
    });

    $(document).on('click','a.inactive', function(){
      var a = $(this).data('value');
      $('#setInActive').attr('href', "{{ url('/') }}/account/actived/"+a);
    });
    @endcan
  });
</script>
@endsection
