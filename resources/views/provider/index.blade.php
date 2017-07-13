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

      <form action="{{ route('ProviderController.store') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Add Provider</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <div class="item form-group {{ $errors->has('provider_name') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Provider Name<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="name" class="form-control col-md-7 col-xs-12" name="provider_name" placeholder="Contoh: Nama Provider" required="required" type="text" value="{{ old('provider_name') }}">
                @if($errors->has('provider_name'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('provider_name')}}</span></code>
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

<div class="modal fade modal-form-update" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="{{ route('ProviderController.update') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Update Provider</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <div class="item form-group {{ $errors->has('provider_name') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Provider <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="id_provider_update" name="id_provider" type="hidden" value="">
                <input id="name_provider_update" class="form-control col-md-7 col-xs-12" name="provider_name" placeholder="Contoh: Nama Provider" required="required" type="text" value="{{ old('provider_name') }}">
                @if($errors->has('provider_name'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('provider_name')}}</span></code>
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

<div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Hapus Produk</h4>
      </div>
      <div class="modal-body">
        <h4>Yakin ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setDelete">Ya</a>
      </div>

    </div>
  </div>
</div>

<div class="page-title">
  <div class="title_left">
    <h3>All Provider <small></small></h3>
  </div>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Provider </h2>
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
              <th>Provider Code</th>
              <th>Provider Name</th>
              <th>Version</th>
              <th>Created By</th>
              <th>Created Date</th>
              <th>Updated By</th>
              <th>Updated Date</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php
              $no = 1;
            @endphp
            @foreach ($getProvider as $key)
            <tr>
              <td>{{ $no }}</td>
              <td>{{ $key->provider_code }}</td>
              <td>{{ $key->provider_name }}</td>
              <td>{!! $key->version !!}</td>
              <td>{!! $key->createdBy->name !!}</td>
              <td>{!! $key->created_at !!}</td>
              <td>{!! $key->updatedBy->name !!}</td>
              <td>{!! $key->updated_at !!}</td>
              <td>
                <!-- <a href="{{ route('ProviderController.update', $key->id) }}" class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-pencil"></i></a> -->
                <a class="update" data-id="{{ $key->id }}" data-name="{{ $key->provider_name }}" data-toggle="modal" data-target=".modal-form-update"><span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-pencil"></i></span></a>
                <a href="" class="delete" data-value="{{ $key->id }}" data-toggle="modal" data-target=".modal-delete"><span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-remove"></i></span></a>
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
    $('.update').click(function() {
        var idProvider    = $(this).data('id');
        var nameProvider  = $(this).data('name');
        $("#id_provider_update").val(idProvider);
        $("#name_provider_update").val(nameProvider);
    });
});

$(function(){
  $('#dataTables').on('click', 'a.delete', function(){
    var a = $(this).data('value');
    $('#setDelete').attr('href', "{{ url('/') }}/provider/delete/"+a);
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
