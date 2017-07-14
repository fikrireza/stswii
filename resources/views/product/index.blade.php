@extends('layout.master')

@section('title')
<<<<<<< HEAD
  <title> | Partner Pulsa</title>
=======
  <title>STS | Product</title>
>>>>>>> 487b11a1333f62b59802d637be48b8ac048eac32
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<<<<<<< HEAD
@endsection

@section('content')
=======
<link href="{{ asset('amadeo/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/pnotify/dist/pnotify.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
@endsection

@section('content')

>>>>>>> 487b11a1333f62b59802d637be48b8ac048eac32
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

<<<<<<< HEAD
<div class="modal fade modal-form-add" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="{{ route('ProviderController.store') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Add Partner Pulsa</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <div class="item form-group {{ $errors->has('provider_name') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Partner Pulsa Name<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="name" class="form-control col-md-7 col-xs-12" name="provider_name" placeholder="Contoh: Nama Partner Pulsa" required="required" type="text" value="{{ old('provider_name') }}">
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
=======
<div class="modal fade modal-unpublish" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Non Active Product</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setUnpublish">Ya</a>
      </div>
>>>>>>> 487b11a1333f62b59802d637be48b8ac048eac32
    </div>
  </div>
</div>

<<<<<<< HEAD
<div class="modal fade modal-form-update" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="{{ route('ProviderController.update') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Update Partner Pulsa</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <div class="item form-group {{ $errors->has('provider_name') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Partner Pulsa <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="id_provider_update" name="id_provider" type="hidden" value="">
                <input id="name_provider_update" class="form-control col-md-7 col-xs-12" name="provider_name" placeholder="Contoh: Nama Partner Pulsa" required="required" type="text" value="{{ old('provider_name') }}">
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
=======
<div class="modal fade modal-publish" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Activated Product</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setPublish">Ya</a>
      </div>
>>>>>>> 487b11a1333f62b59802d637be48b8ac048eac32
    </div>
  </div>
</div>

<div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
<<<<<<< HEAD
        <h4 class="modal-title" id="myModalLabel2">Hapus Produk</h4>
      </div>
      <div class="modal-body">
        <h4>Yakin ?</h4>
=======
        <h4 class="modal-title" id="myModalLabel2">Delete Product</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
>>>>>>> 487b11a1333f62b59802d637be48b8ac048eac32
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setDelete">Ya</a>
      </div>

    </div>
  </div>
</div>

<<<<<<< HEAD
<div class="page-title">
  <div class="title_left">
    <h3>All Partner Pulsa <small></small></h3>
  </div>
</div>
=======

<div class="page-title">
  <div class="title_left">
    <h3>All Product <small></small></h3>
  </div>
</div>

>>>>>>> 487b11a1333f62b59802d637be48b8ac048eac32
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
<<<<<<< HEAD
        <h2>Partner Pulsa </h2>
        <ul class="nav panel_toolbox">
          <a class="btn btn-success btn-sm publish" data-toggle="modal" data-target=".modal-form-add" ><i class="fa fa-plus"></i> Add</a>
=======
        <h2>Product </h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('product.tambah') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
>>>>>>> 487b11a1333f62b59802d637be48b8ac048eac32
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
<<<<<<< HEAD
        <table id="dataTables" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Partner Pulsa Code</th>
              <th>Partner Pulsa Name</th>
              <th>Version</th>
              <th>Created By</th>
              <th>Created Date</th>
              <th>Updated By</th>
              <th>Updated Date</th>
              <th>Aksi</th>
=======
        <table id="producttabel" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Product Code</th>
              <th>Product Name</th>
              <th>Provider</th>
              <th>Nominal</th>
              <th>Status</th>
              <th>Action</th>
>>>>>>> 487b11a1333f62b59802d637be48b8ac048eac32
            </tr>
          </thead>
          <tbody>
            @php
              $no = 1;
            @endphp
<<<<<<< HEAD
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
=======
            @foreach ($getProduct as $key)
            <tr>
              <td>{{ ++$no }}</td>
              <td>{{ $key->product_code }}</td>
              <td>{{ $key->product_name }}</td>
              <td>{{ $key->provider->provider_name }}</td>
              <td>{{ number_format($key->nominal, 0, ',', '.') }}</td>
              <td class="text-center">@if ($key->active == 1)
                    <a href="" class="unpublish" data-value="{{ $key->id }}" data-toggle="modal" data-target=".modal-unpublish"><span class="label label-success" data-toggle="tooltip" data-placement="top" title="Publish"><i class="fa fa-thumbs-o-up"></i></span></a>
                    <br>
                    <span class="label label-primary">{{ $key->active_datetime }}</span>
                  @else
                    <a href="" class="publish" data-value="{{ $key->id }}" data-toggle="modal" data-target=".modal-publish"><span class="label label-danger" data-toggle="tooltip" data-placement="top" title="Unpublish"><i class="fa fa-thumbs-o-down"></i></span></a>
                    <br>
                    <span class="label label-primary">{{ $key->non_active_datetime }}</span>
                  @endif
              </td>
              <td>
                <a href="{{ route('product.ubah', $key->product_code) }}" class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-pencil"></i></a>
                {{-- <a href="{{ route('product.lihat', $key->id) }}" class="btn btn-xs btn-primary" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="fa fa-folder"></i></a> --}}
                <a href="" class="delete" data-value="{{ $key->product_code }}" data-toggle="modal" data-target=".modal-delete"><span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-remove"></i></span></a>
              </td>
            </tr>
>>>>>>> 487b11a1333f62b59802d637be48b8ac048eac32
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<<<<<<< HEAD
=======

>>>>>>> 487b11a1333f62b59802d637be48b8ac048eac32
@endsection

@section('script')
<script src="{{ asset('amadeo/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-scroller/js/datatables.scroller.min.js') }}"></script>
<<<<<<< HEAD
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

=======
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>

<script type="text/javascript">
  $('#producttabel').DataTable();

  $(function(){
    $('#producttabel').on('click','a.unpublish', function(){
      var a = $(this).data('value');
      $('#setUnpublish').attr('href', "{{ url('/') }}/admin/Product/publish/"+a);
    });
  });

  $(function(){
    $('#producttabel').on('click', 'a.publish', function(){
      var a = $(this).data('value');
      $('#setPublish').attr('href', "{{ url('/') }}/admin/Product/publish/"+a);
    });
  });

  $(function(){
    $('#producttabel').on('click', 'a.delete', function(){
      var a = $(this).data('value');
      $('#setDelete').attr('href', "{{ url('/') }}/admin/Product/delete/"+a);
    });
  });
</script>
>>>>>>> 487b11a1333f62b59802d637be48b8ac048eac32
@endsection
