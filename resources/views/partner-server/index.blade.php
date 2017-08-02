@extends('layout.master')

@section('title')
  <title> | Provider</title>
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

<div class="modal fade modal-form-add" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="{{ route('partner-server.store') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Add Partner Server</h4>
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
                <input class="form-control col-md-7 col-xs-12" name="api_key" placeholder="Eg: Api Key" required="required" type="text" value="{{ old('api_key', base64_encode(openssl_random_pseudo_bytes(12))) }}">
                @if($errors->has('api_key'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('api_key')}}</span></code>
                @endif
              </div>
            </div>
            <div class="item form-group {{ $errors->has('api_secret') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Api Secret<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input class="form-control col-md-7 col-xs-12" name="api_secret" placeholder="Eg: Api Secret" required="required" type="text" value="{{ old('api_secret', base64_encode(openssl_random_pseudo_bytes(32))) }}">
                @if($errors->has('api_secret'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('api_secret')}}</span></code>
                @endif
              </div>
            </div>

        </div>
        <div class="modal-footer">
          <button id="send" type="submit" class="btn btn-success" disabled="true">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade modal-form-update" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="{{ route('partner-server.update') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Update Partner Server</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <input id="id_data" name="id_data" type="hidden" value="">
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

        </div>
        <div class="modal-footer">
          <button id="send" type="submit" class="btn btn-success" disabled="true">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Delete Partner Server</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary disabled" id="setDelete">Yes</a>
      </div>

    </div>
  </div>
</div>

<div class="page-title">
  <div class="title_left">
    <h3>All Partner Server <small></small></h3>
  </div>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Partner Server </h2>
        <ul class="nav panel_toolbox">
          <a class="btn btn-success btn-sm publish" data-toggle="modal" data-target=".modal-form-add" ><i class="fa fa-plus"></i> Add</a>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table id="dataTables" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Partner Pulsa Code</th>
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
            @for ($i=1; $i < 4; $i++)
            <tr>
              <td>{{ $no }}</td>
              <td>Partner.{{ rand(10,99) }}</td>
              <td>http://example.com/api/v2/bla-bla-boom</td>
              <td>{{ base64_encode(openssl_random_pseudo_bytes(12)) }}</td>
              <td>{{ base64_encode(openssl_random_pseudo_bytes(32)) }}</td>
              <td>
                <a
                  class="update"
                  data-id="{{ $i }}"
                  data-url="http://example.com/api/v2/bla-bla-boom"
                  data-apikey="{{ base64_encode(openssl_random_pseudo_bytes(12)) }}"
                  data-apisecret="{{ base64_encode(openssl_random_pseudo_bytes(32)) }}"
                  data-toggle="modal"
                  data-target=".modal-form-update"
                >
                  <span class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-pencil"></i></span>
                </a>
                <a
                  href=""
                  class="delete"
                  data-value="{{ $i }}"
                  data-toggle="modal"
                  data-target=".modal-delete"
                >
                  <span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-remove"></i></span>
                </a>
              </td>
            </tr>
            @php
              $no++;
            @endphp
            @endfor
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
    $('.update').click(function() {
        var dataId    = $(this).data('id');
        var dataUrl  = $(this).data('url');
        var dataApiK  = $(this).data('apikey');
        var dataApiS  = $(this).data('apisecret');
        $("#id_data").val(dataId);
        $("#server_url_data").val(dataUrl);
        $("#api_key_data").val(dataApiK);
        $("#api_secret_data").val(dataApiS);
    });
});

$(function(){
  $('#dataTables').on('click', 'a.delete', function(){
    var a = $(this).data('value');
    $('#setDelete').attr('href', "{{ url('/') }}/partner-server/delete/"+a);
  });
});
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
