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

@can('create-provider')
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
            <div class="item form-group {{ $errors->has('provider_code') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Provider Code<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="code" class="form-control col-md-7 col-xs-12" name="provider_code" placeholder="E.g: AXIATA" type="text" value="{{ old('provider_code')}}" onchange="this.value = this.value.toUpperCase()">
                @if($errors->has('provider_code'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('provider_code')}}</span></code>
                @endif
              </div>
            </div>
            <div class="item form-group {{ $errors->has('provider_name') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Provider Name<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="name" class="form-control col-md-7 col-xs-12" name="provider_name" placeholder="E.g: Xl" required="required" type="text" value="{{ old('provider_name') }}" onchange="this.value = this.value.toUpperCase()">
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
@endcan

@can('update-provider')
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

                <input id="id_provider_update" name="provider_id" type="hidden" value="{{ old('provider_id') }}">
                <input id="version_provider_update" name="version" type="hidden" value="{{ old('version') }}">
                <input id="code_provider_update" class="form-control col-md-7 col-xs-12" name="provider_code" type="text" value="{{ old('provider_code') }}" onchange="this.value = this.value.toUpperCase()">
                @if($errors->has('provider_code'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('provider_code')}}</span></code>
                @endif
              </div>
            </div>
            <div class="item form-group {{ $errors->has('provider_name') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Provider Name <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="name_provider_update" class="form-control col-md-7 col-xs-12" name="provider_name" placeholder="E.g: XL" required="required" type="text" value="{{ old('provider_name') }}" onchange="this.value = this.value.toUpperCase()">
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
@endcan

@can('delete-provider')
<div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Delete Provider</h4>
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


<div class="modal fade modal-form-read" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">View Provider</h4>
      </div>
      <div id="modal-form-read-html" class="modal-body">

      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>

<div class="modal fade modal-form-update-prefix" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form action="{{ route('provider-prefix.update') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Update Provider</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <div class="item form-group {{ $errors->has('provider_id') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Provider <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="prefix_id_update" name="provider_prefix_id" type="hidden" value="{{ old('provider_prefix_id') }}">
                <input id="version_update" name="version" type="hidden" value="{{ old('version') }}">
                <select id="provider_id_update" name="provider_id" class="form-control select2_single" required="required">
                  <option value="">Pilih</option>
                  @foreach($getProvider as $list)
                  <option value="{{ $list->provider_id }}" {{ old('provider_id') == $list->provider_id ? 'selected' : '' }}>
                    {{ $list->provider_code.' ('.$list->provider_name.')' }}
                  </option>
                  @endforeach
                </select>
                @if($errors->has('provider_id'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('provider_id')}}</span></code>
                @endif
              </div>
            </div>
            <div class="item form-group {{ $errors->has('prefix') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                Provider Prefix<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="prefix_update" class="form-control col-md-7 col-xs-12" name="prefix" placeholder="E.g: 0808" required="required" type="text" onkeypress="return isNumber(event)" value="{{ old('prefix') }}">
                @if($errors->has('prefix'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('prefix')}}</span></code>
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
        <h4 class="modal-title" id="myModalLabel2">Delete Provider Prefix</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setDeletePrefix">Yes</a>
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
          @can('create-provider')
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
              <th>Provider Code</th>
              <th>Provider Name</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tfoot>
            <td></td>
            <th></th>
            <th></th>
            <td></td>
          </tfoot>
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
    $('#dataTables').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('provider.yajra.getDatas') }}",
        columns: [
            {data: 'slno', name: 'No'},
            {data: 'provider_code'},
            {data: 'provider_name'},
            {data: 'action', name: 'Action', orderable: false, searchable: false}
        ]
    });

    // $('#dataTables tfoot th').each( function () {
    //   var title = $(this).text();
    //   $(this).html( '<input type="text" class="form-control" style="border:1px solid #ceeae8; width:100%" />' );
    // });
    //
    // var table = $('#dataTables').DataTable();
    // table.columns().every( function () {
    //     var that = this;
    //     $( 'input', this.footer() ).on( 'keyup change', function () {
    //         if ( that.search() !== this.value ) {
    //             that
    //             .search( this.value )
    //             .draw();
    //         }
    //     });
    // });
});

$(function(){
  @can('read-provider-prefix')
  $(document).on('click', '.read', function(e) {
      var idProvider    = $(this).data('id');
      $.ajax(
        {
            url: "{{ url('/') }}/provider/ajax-view/" + idProvider,
            type: "get"
        })
        .done(function(data)
        {
            $("#modal-form-read-html.modal-body").html(data.html);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError)
        {
              alert('server not responding...');
        });
  });
  @endcan

  @can('update-provider')
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
  @endcan

  @can('update-provider-prefix')
  $(document).on('click', '.update-prefix', function(e) {
      var provider_id = $(this).data('provider_id');
      var prefix_id   = $(this).data('prefix_id');
      var prefix      = $(this).data('prefix');
      var version      = $(this).data('version');
      $("#provider_id_update").val(provider_id);
      $("#prefix_id_update").val(prefix_id);
      $("#prefix_update").val(prefix);
      $("#version_update").val(version);
  });
  @endcan

  @can('delete-provider-prefix')
  $(document).on('click', '.delete-prefix', function(e) {
    var a = $(this).data('value');
    var b = $(this).data('version');
    $('#setDeletePrefix').attr('href', "{{ url('/') }}/provider-prefix/delete/"+a+"/"+b);
  });
  @endcan
});

@can('delete-provider')
$(function(){
  $('#dataTables').on('click', 'a.delete', function(){
    var a = $(this).data('value');
    var b = $(this).data('version');
    $('#setDelete').attr('href', "{{ url('/') }}/provider/delete/"+a+"/"+b);
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
