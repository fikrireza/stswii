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
    $(".alert.alert-dismissible").fadeTo(700, 0).slideUp(700, function(){
        $(this).remove();
    });
  }, 5000);
</script>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="alert {{ Session::get('alret') }} alert-dismissible fade in" role="alert">
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
      <form action="{{ route('provider.store') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Add Provider</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Provider Code<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input 
                  id="code" 
                  class="form-control col-md-7 col-xs-12" 
                  name="provider_code" 
                  type="text" 
                  value="{{ $newProvCode }}"
                  readonly
                >
              </div>
            </div>
            <div class="item form-group {{ $errors->has('provider_name') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Provider Name<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input 
                  id="name" 
                  class="form-control col-md-7 col-xs-12" 
                  name="provider_name" 
                  placeholder="Contoh: Nama Provider" 
                  required="required" 
                  type="text" 
                  value="{{ old('provider_name') }}"
                >
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
      <form action="{{ route('provider.update') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Update Provider</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <div class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Provider Code<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input 
                  id="id_provider_update" 
                  name="provider_id" 
                  type="hidden" 
                  value="{{ old('provider_id') }}" 
                >
                <input 
                  id="version_provider_update" 
                  name="version" 
                  type="hidden" 
                  value="{{ old('version') }}" 
                >
                <input 
                  id="code_provider_update" 
                  class="form-control col-md-7 col-xs-12" 
                  name="provider_code"
                  type="text" 
                  value="{{ old('provider_code') }}"
                  readonly
                >
              </div>
            </div>
            <div class="item form-group {{ $errors->has('provider_name') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Nama Provider <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input 
                  id="name_provider_update" 
                  class="form-control col-md-7 col-xs-12" 
                  name="provider_name" 
                  placeholder="Contoh: Nama Provider" 
                  required="required" 
                  type="text" 
                  value="{{ old('provider_name') }}"
                >
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
        <h4 class="modal-title" id="myModalLabel2">Hapus Provider</h4>
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

<div class="modal fade modal-form-read" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">View Provider</h4>
      </div>
      <div class="modal-body">
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">
              Provider Code :
            </label>
            <label id="provider-prefix-code" class="col-md-3 col-sm-3 col-xs-12">

            </label>
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
              Provider Name :
            </label>
            <label id="provider-prefix-name" class="col-md-3 col-sm-3 col-xs-12">

            </label>
          </div>
          <div id="provider-prefix-number" class="item form-group col-md-12 col-sm-12 col-xs-12" style="border-top: solid 1px rgb(229,229,229);">
            <label for="prefix">
              Provider Prefix 
            </label>
          </div>
          <div class="clearfix"></div>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>

<div class="modal fade modal-form-update-prefix" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="{{ route('provider.update') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Update Provider Prefix</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <div class="item form-group {{ $errors->has('provider_name') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Provider Prefix <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="id_provider_prefix_update" name="id_provider_prefix" type="hidden" value="">
                <input id="provider_prefix_update" class="form-control col-md-7 col-xs-12" name="provider_prefix" placeholder="Contoh: Provider Prefix" required="required" type="text" value="{{ old('provider_name') }}" onkeypress="return isNumber(event)">
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

<div class="modal fade modal-delete-prefix" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Hapus Provider Prefix</h4>
      </div>
      <div class="modal-body">
        <h4>Yakin ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setDeletePrefix">Ya</a>
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
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php ($no = 1)
            @foreach($getProvider as $list)
            <tr>
              <td>{{ $no++ }}</td>
              <td>{{ $list->provider_code or '-' }}</td>
              <td>{{ $list->provider_name or '-' }}</td>
              <td>
                <a 
                  class="read" 
                  data-id="{{ $list->provider_id }}"
                  @if($list->count_provider_prefix != 0)
                  data-toggle="modal" 
                  data-target=".modal-form-read"
                  @endif
                >
                  <span class="btn btn-xs btn-info btn-sm {{ $list->count_provider_prefix == 0 ? 'disabled' : '' }}" data-toggle="tooltip" data-placement="top" title="View"><i class="fa fa-archive"></i></span>
                </a>
                <a 
                  class="update" 
                  data-id="{{ $list->provider_id }}" 
                  data-code="{{ $list->provider_code or '-' }}" 
                  data-name="{{ $list->provider_name or '-' }}" 
                  data-version="{{ $list->version or '-' }}" 
                  data-toggle="modal" 
                  data-target=".modal-form-update"
                >
                  <span class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-pencil"></i></span>
                </a>
                <a 
                  href="" 
                  class="delete" 
                  data-value="{{ $list->provider_id }}" 
                  data-toggle="modal" 
                  data-target=".modal-delete"
                >
                  <span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-remove"></i></span>
                </a>
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
$('#dataTables').DataTable();

$(function(){
    $(document).on('click', '.read', function(e) {
        var provId    = $(this).data('id');
        var provCode  = $(this).data('code');
        var provName  = $(this).data('name');

        var proviPrefix1  = ['0','0812','0813','0814','0815'];
        var proviPrefix2  = ['0','0856','0857','0859'];
        var proviPrefix3  = ['0','0822','0825','0828'];
        var proviPrefix4  = ['0','0896','0897'];

        $('#provider-prefix-code').html(provCode);
        $('#provider-prefix-name').html(provName);

        if(provId == 1){
          // alert(proviPrefix1.length);
          $(".prefix-number-append").remove();
          var forLenght = (proviPrefix1.length-1);
          for(i=1; i<=forLenght; i++){
            $("#provider-prefix-number").append(
              "<div class='prefix-number-append item form-group col-md-12 col-sm-12 col-xs-12'><label class='col-md-3 col-sm-3 col-xs-6'>" + proviPrefix1[i] + "</label><label class='col-md-3 col-sm-3 col-xs-6'><a class='update-prefix' data-id='" + i + "' data-prefix='" + proviPrefix1[i] + "' data-toggle='modal' data-target='.modal-form-update-prefix'><span class='btn btn-xs btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='Update'><i class='fa fa-pencil'></i></span></a></label><label class='col-md-3 col-sm-3 col-xs-6'><a href='' class='delete-prefix' data-value='" + i + "' data-toggle='modal' data-target='.modal-delete-prefix'><span class='btn btn-xs btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Hapus'><i class='fa fa-remove'></i></span></a></label>"
            );
          }
        }

        if(provId == 2){
          // alert(proviPrefix1.length);
          $(".prefix-number-append").remove();
          var forLenght = (proviPrefix2.length-1);
          for(i=1; i<=forLenght; i++){
            $("#provider-prefix-number").append(
              "<div class='prefix-number-append item form-group col-md-12 col-sm-12 col-xs-12'><label class='col-md-3 col-sm-3 col-xs-6'>" + proviPrefix2[i] + "</label><label class='col-md-3 col-sm-3 col-xs-6'><a class='update-prefix' data-id='" + i + "' data-prefix='" + proviPrefix2[i] + "' data-toggle='modal' data-target='.modal-form-update-prefix'><span class='btn btn-xs btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='Update'><i class='fa fa-pencil'></i></span></a></label><label class='col-md-3 col-sm-3 col-xs-6'><a href='' class='delete-prefix' data-value='" + i + "' data-toggle='modal' data-target='.modal-delete-prefix'><span class='btn btn-xs btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Hapus'><i class='fa fa-remove'></i></span></a></label>"
            );
          }
        }

        if(provId == 3){
          // alert(proviPrefix1.length);
          $(".prefix-number-append").remove();
          var forLenght = (proviPrefix3.length-1);
          for(i=1; i<=forLenght; i++){
            $("#provider-prefix-number").append(
              "<div class='prefix-number-append item form-group col-md-12 col-sm-12 col-xs-12'><label class='col-md-3 col-sm-3 col-xs-6'>" + proviPrefix3[i] + "</label><label class='col-md-3 col-sm-3 col-xs-6'><a class='update-prefix' data-id='" + i + "' data-prefix='" + proviPrefix3[i] + "' data-toggle='modal' data-target='.modal-form-update-prefix'><span class='btn btn-xs btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='Update'><i class='fa fa-pencil'></i></span></a></label><label class='col-md-3 col-sm-3 col-xs-6'><a href='' class='delete-prefix' data-value='" + i + "' data-toggle='modal' data-target='.modal-delete-prefix'><span class='btn btn-xs btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Hapus'><i class='fa fa-remove'></i></span></a></label>"
            );
          }
        }

        if(provId == 4){
          // alert(proviPrefix1.length);
          $(".prefix-number-append").remove();
          var forLenght = (proviPrefix4.length-1);
          for(i=1; i<=forLenght; i++){
            $("#provider-prefix-number").append(
              "<div class='prefix-number-append item form-group col-md-12 col-sm-12 col-xs-12'><label class='col-md-3 col-sm-3 col-xs-6'>" + proviPrefix4[i] + "</label><label class='col-md-3 col-sm-3 col-xs-6'><a class='update-prefix' data-id='" + i + "' data-prefix='" + proviPrefix4[i] + "' data-toggle='modal' data-target='.modal-form-update-prefix'><span class='btn btn-xs btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='Update'><i class='fa fa-pencil'></i></span></a></label><label class='col-md-3 col-sm-3 col-xs-6'><a href='' class='delete-prefix' data-value='" + i + "' data-toggle='modal' data-target='.modal-delete-prefix'><span class='btn btn-xs btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Hapus'><i class='fa fa-remove'></i></span></a></label>"
            );
          }
        }
    });

    $(document).on('click', '.update', function(e) {
        var idProvider    = $(this).data('id');
        var codeProvider  = $(this).data('code');
        var versionProvider  = $(this).data('version');
        var nameProvider  = $(this).data('name');
        $("#id_provider_update").val(idProvider);
        $("#code_provider_update").val(codeProvider);
        $("#version_provider_update").val(versionProvider);
        $("#name_provider_update").val(nameProvider);
    });
    
    $(document).on('click', '.update-prefix', function(e) {
        var idProvider    = $(this).data('id');
        var prefixProvider  = $(this).data('prefix');
        $("#id_provider_prefix_update").val(idProvider);
        $("#provider_prefix_update").val(prefixProvider);
    });    
});

$(function(){
  $('#dataTables').on('click', 'a.delete', function(){
    var a = $(this).data('value');
    $('#setDelete').attr('href', "{{ url('/') }}/provider/delete/"+a);
  });
});

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
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
