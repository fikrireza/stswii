@extends('layout.master')

@section('title')
  <title> | Supplier Server</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
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

@can('create-partner-server')
<div class="modal fade modal-form-add" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="{{ route('partner-server.store') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Add Supplier Server</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <div class="item form-group {{ $errors->has('server_url') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Server Url<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input class="form-control col-md-7 col-xs-12" name="server_url" placeholder="Eg: Server Url" required="required" type="text" value="{{ old('server_url') }}">
                @if($errors->has('server_url'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('server_url')}}</span></code>
                @endif
              </div>
            </div>
            <div class="item form-group {{ $errors->has('api_key') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Api Key<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input class="form-control col-md-7 col-xs-12" name="api_key" placeholder="Eg: Api Key" required="required" type="text" value="{{ old('api_key') }}">
                @if($errors->has('api_key'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('api_key')}}</span></code>
                @endif
              </div>
            </div>
            <div class="item form-group {{ $errors->has('api_secret') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Api Secret<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input class="form-control col-md-7 col-xs-12" name="api_secret" placeholder="Eg: Api Secret" required="required" type="text" value="{{ old('api_secret') }}">
                @if($errors->has('api_secret'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('api_secret')}}</span></code>
                @endif
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
@can('update-partner-server')
<div class="modal fade modal-form-update" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="{{ route('partner-server.update') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Update Supplier Server</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <input id="id_data" name="id_data" type="hidden" value="{{ old('id_data') }}">
            <input id="version" name="version" type="hidden" value="{{ old('version') }}">
            <div class="item form-group {{ $errors->has('server_url') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Server Url <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="server_url_data" class="form-control col-md-7 col-xs-12" name="server_url" placeholder="Eg: http://example.com" required="required" type="text" value="{{ old('server_url') }}">
                @if($errors->has('server_url'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('server_url')}}</span></code>
                @endif
              </div>
            </div>
            <div class="item form-group {{ $errors->has('api_key') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Api Key <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="api_key_data" class="form-control col-md-7 col-xs-12" name="api_key" placeholder="Eg: 12345678" required="required" type="text" value="{{ old('api_key') }}">
                @if($errors->has('api_key'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('api_key')}}</span></code>
                @endif
              </div>
            </div>
            <div class="item form-group {{ $errors->has('api_secret') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Api Secret <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="api_secret_data" class="form-control col-md-7 col-xs-12" name="api_secret" placeholder="Eg: 123hdoDif833y72hhsas33DEfjdhe" required="required" type="text" value="{{ old('api_secret') }}">
                @if($errors->has('api_secret'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('api_secret')}}</span></code>
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
@can('delete-partner-server')
<div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Delete Supplier Server</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setDelete">Yes</a>
      </div>

    </div>
  </div>
</div>
@endcan

<div class="page-title">
  <div class="title_left">
    <h3>All Supplier Server <small></small></h3>
  </div>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Supplier Server </h2>
        <ul class="nav panel_toolbox">
          @can('create-partner-server')
          <a class="btn btn-success btn-sm publish" data-toggle="modal" data-target=".modal-form-add" ><i class="fa fa-plus"></i> Add</a>
          @endcan
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table id="dataTables" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Server Url</th>
              <th>Api Key</th>
              <th>Api Secret</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php
              $no = 1;
            @endphp
            @foreach($index as $list)
            <tr>
              <td>{{ $no }}</td>
              <td>{{ $list->server_url }}</td>
              <td>{{ $list->api_key }}</td>
              <td>{{ $list->api_secret }}</td>
              <td>
                @can('update-partner-server')
                <a
                  class="update"
                  data-id="{{ $list->partner_pulsa_id }}"
                  data-version="{{ $list->version }}"
                  data-url="{{ $list->server_url }}"
                  data-apikey="{{ $list->api_key }}"
                  data-apisecret="{{ $list->api_secret }}"
                  data-toggle="modal"
                  data-target=".modal-form-update"
                >
                  <span class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-pencil"></i></span>
                </a>
                @endcan
                @can('delete-partner-server')
                <a
                  href=""
                  class="delete"
                  data-value="{{ $list->partner_pulsa_id }}"
                  data-toggle="modal"
                  data-target=".modal-delete"
                >
                  <span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></span>
                </a>
                @endcan
              </td>
            </tr>
            @php
              $no++;
            @endphp
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
$('#dataTables').DataTable();

$(function(){
    $(document).on('click', '.update', function(){
        var dataId    = $(this).data('id');
        var dataVersion = $(this).data('version');
        var dataUrl  = $(this).data('url');
        var dataApiK  = $(this).data('apikey');
        var dataApiS  = $(this).data('apisecret');
        $("#id_data").val(dataId);
        $("#version").val(dataVersion);
        $("#server_url_data").val(dataUrl);
        $("#api_key_data").val(dataApiK);
        $("#api_secret_data").val(dataApiS);
    });
});

@can('delete-partner-server')
$(function(){
  $('#dataTables').on('click', 'a.delete', function(){
    var a = $(this).data('value');
    $('#setDelete').attr('href', "{{ url('/') }}/partner-server/delete/"+a);
  });
});
@endcan
</script>

@if(Session::has('add-false'))
<script>
  $('.modal-form-add').modal('show');
</script>
@endif
@if(Session::has('update-false'))
<script>
  $('.modal-form-update').modal('show');
</script>
@endif

@endsection
