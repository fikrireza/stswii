@extends('layout.master')

@section('title')
  <title> | Partner Pulsa</title>
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

@can('delete-partner-pulsa')
<div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Hapus Partner Pulsa</h4>
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
@endcan
@can('activate-partner-pulsa')
<div class="modal fade modal-nonactive" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Nonactive Partner Pulsa</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setUnpublish">Ya</a>
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
        <h4 class="modal-title" id="myModalLabel2">Activated Partner Pulsa</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setPublish">Ya</a>
      </div>
    </div>
  </div>
</div>
@endcan

<div class="page-title">
  <div class="title_left">
    <h3>All Partner Pulsa <small></small></h3>
  </div>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Partner Pulsa </h2>
        <ul class="nav panel_toolbox">
          @can('create-partner-pulsa')
          <a class="btn btn-success btn-sm publish" href="{{ route('partner-pulsa.create') }}"><i class="fa fa-plus"></i> Add</a>
          @endcan
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table id="dataTables" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Partner Code</th>
              <th>Partner Name</th>
              <th>Description</th>
              <th>Type TOP</th>
              <th>Payment Termin</th>
              @can('activate-partner-pulsa')
              <th>Status</th>
              @endcan
              <th>Aksi</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <td></td>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              @can('activate-partner-pulsa')
              <th></th>
              @endcan
              <td></td>
            </tr>
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
        ajax: "{{ route('partner-pulsa.yajra.getDatas') }}",
        columns: [
            {data: 'slno', name: 'No'},
            {data: 'partner_pulsa_code'},
            {data: 'partner_pulsa_name'},
            {data: 'description'},
            {data: 'type_top'},
            {data: 'payment_termin'},
            @can('activate-partner-pulsa')
              {data: 'active', orderable: false, searchable: false},
            @endcan
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
  $(document).on('click','a.delete', function(){
    var a = $(this).data('value');
    var b = $(this).data('version');
    $('#setDelete').attr('href', "{{ url('/') }}/partner-pulsa/delete/"+a+"/"+b);
  });

  $(document).on('click', 'a.publish', function(){
    var a = $(this).data('value');
    var b = $(this).data('version');
    $('#setPublish').attr('href', "{{ url('/') }}/partner-pulsa/actived/"+a+"/"+b+"/Y");
  });

  $(document).on('click','a.unpublish', function(){
    var a = $(this).data('value');
    var b = $(this).data('version');
    $('#setUnpublish').attr('href', "{{ url('/') }}/partner-pulsa/actived/"+a+"/"+b+"/N");
  });
});

</script>
@endsection
