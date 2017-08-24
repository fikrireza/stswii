@extends('layout.master')

@section('title')
  <title> | Supplier Product</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
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

@can('delete-partner-product')
<div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Delete Supplier Product</h4>
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
@can('activate-partner-product')
<div class="modal fade modal-nonactive" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Nonactive Supplier Product</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setUnpublish">Yes</a>
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
        <h4 class="modal-title" id="myModalLabel2">Activated Supplier Product</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setPublish">Yes</a>
      </div>
    </div>
  </div>
</div>
@endcan

<div class="page-title">
  <div class="title_left">
    <h3>All Supplier Product <small></small></h3>
  </div>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Supplier Product </h2>
        <ul class="nav panel_toolbox">
          @can('create-partner-product')
          <a class="btn btn-success btn-sm publish" href="{{ route('partner-product.create') }}"><i class="fa fa-plus"></i> Add</a>
          @endcan
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">

        <form class="form-inline text-center">
          <select name="f_provider" class="form-control select_provider" onchange="this.form.submit()">
            <option value="">Filter Provider</option>
            @foreach($provider as $list)
                <option
                  value="{{$list->provider_id}}"
                  @if($request->f_provider == $list->provider_id) selected @endif
                >
                  {{$list->provider_name}}
                </option>
            @endforeach
          </select>
          <select name="f_partner" class="form-control select_partner" onchange="this.form.submit()">
            <option value="">Filter Supplier</option>
            @foreach($partner as $list)
                <option
                  value="{{$list->partner_pulsa_id}}"
                  @if($request->f_partner == $list->partner_pulsa_id) selected @endif
                >
                  {{$list->partner_pulsa_name."/".$list->partner_pulsa_code}}
                </option>
            @endforeach
          </select>
          <select name="f_active" class="form-control select_status" onchange="this.form.submit()">
            <option value="" @if(isset($request->f_active) && $request->f_active == '') selected @endif>All Status</option>
            <option value="Y" @if(isset($request->f_active) && $request->f_active == 'Y') selected @endif>Active</option>
            <option value="N" @if(isset($request->f_active) && $request->f_active == 'N') selected @endif>Not Active</option>
          </select>
        </form>
        <div class="ln_solid"></div>

        <table id="dataTables" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Supplier Pulsa Code</th>
              <th>Provider Code</th>
              <th>Product Code</th>
              <th>Supplier Product Code</th>
              <th>Supplier Product Name</th>
              @can('activate-partner-product')
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
              @can('activate-partner-product')
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
<script src="{{ asset('amadeo/vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-scroller/js/datatables.scroller.min.js') }}"></script>

@if(isset($request))
<script type="text/javascript">
$(function() {
    $('#dataTables').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('partner-product.yajra.getDatas') }}?f_provider={{ $request->f_provider }}&f_partner={{ $request->f_partner }}&f_active={{ $request->f_active }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'partner_pulsa_code'},
            {data: 'provider_code'},
            {data: 'product_code'},
            {data: 'partner_product_code'},
            {data: 'partner_product_name'},
            @can('activate-partner-product')
              {data: 'active', name: 'Status', orderable: false, searchable: false},
            @endcan
            {data: 'action', name: 'Action', orderable: false, searchable: false}
        ]
    });
});
</script>
@else
<script type="text/javascript">
$(function() {
    $('#dataTables').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('partner-product.yajra.getDatas') }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'partner_pulsa_code'},
            {data: 'provider_code'},
            {data: 'product_code'},
            {data: 'partner_product_code'},
            {data: 'partner_product_name'},
            @can('activate-partner-product')
              {data: 'active', name: 'Status', orderable: false, searchable: false},
            @endcan
            {data: 'action', name: 'Action', orderable: false, searchable: false}
        ],
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                var input = document.createElement("input");
                $(input).appendTo($(column.footer()).empty())
                .on('change', function () {
                    column.search($(this).val(), false, false, true).draw();
                });
            });
        }
    });
});
</script>
@endif

<script type="text/javascript">
$(".select_provider").select2({
  placeholder: "Filter Provider",
  allowClear: true
});

$(".select_status").select2({
  placeholder: "Filter Status",
  allowClear: true
});

$(".select_partner").select2({
  placeholder: "Filter Supplier",
  allowClear: true
});

$(function(){
  @can('delete-partner-product')
  $(document).on('click','a.delete', function(){
    var a = $(this).data('value');
    var b = $(this).data('version');
    $('#setDelete').attr('href', "{{ url('/') }}/partner-product/delete/"+a+"/"+b);
  });
  @endcan

  @can('activate-partner-product')
  $(document).on('click', 'a.publish', function(){
    var a = $(this).data('value');
    var b = $(this).data('version');
    $('#setPublish').attr('href', "{{ url('/') }}/partner-product/actived/"+a+"/"+b+"/Y");
  });

  $(document).on('click','a.unpublish', function(){
    var a = $(this).data('value');
    var b = $(this).data('version');
    $('#setUnpublish').attr('href', "{{ url('/') }}/partner-product/actived/"+a+"/"+b+"/N");
  });
  @endcan
});

</script>
@endsection
