@extends('layout.master')

@section('title')
  <title>STS | Product MLM</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
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

@if(Session::has('gagal'))
<script>
  window.setTimeout(function() {
    $(".alert-danger").fadeTo(700, 0).slideUp(700, function(){
        $(this).remove();
    });
  }, 15000);
</script>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
      </button>
      <strong>{{ Session::get('gagal') }}</strong>
    </div>
  </div>
</div>
@endif


@can('delete-product-mlm')
<div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Delete Product</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setDelete">Ya</a>
      </div>

    </div>
  </div>
</div>
@endcan

<div class="page-title">
  <div class="title_left">
    <h3>All Product MLM<small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Product MLM</h2>
        <ul class="nav panel_toolbox">
          @can('create-product')
          <a href="{{ route('product-mlm.tambah') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
          @endcan
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <form class="form-inline text-center">
          <select name="f_provider" class="form-control select_provider" onchange="this.form.submit()">
            <option value="">Filter Provider</option>
            @foreach($provider as $list)
                <option value="{{$list->provider_id}}" @if($request->f_provider == $list->provider_id) selected @endif>{{$list->provider_name}}</option>
            @endforeach
          </select>

          <select name="f_type_product" class="form-control select_type_product" onchange="this.form.submit()">
            <option value="">Filter Type Product</option>
            <option value="PULSA" @if($request->f_type_product == 'PULSA') selected @endif>PULSA</option>
            <option value="DATA" @if($request->f_type_product == 'DATA') selected @endif>DATA</option>          
          </select>
        </form>
        <div class="ln_solid"></div>

        <table id="producttabel" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Provider Code</th>
              <th>Product Code</th>
              <th>Product Name</th>
              <th>Nominal</th>
              <th>Type Product</th>
              <th>Sort Number</th>              
              <th>Status</th>              
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <td></td>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
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
<script src="{{ asset('amadeo/vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-scroller/js/datatables.scroller.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>

@if(isset($request))
<script type="text/javascript">
$(function() {
    $('#producttabel').DataTable({
        processing: true,
        serverSide: true,
        "pageLength": 100,
        ajax: "{{ route('product-mlm.yajra.getDatas') }}?f_provider={{ $request->f_provider }}&f_type_product={{ $request->f_type_product }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'provider_code'},
            {data: 'product_code'},
            {data: 'product_name'},
            {data: 'nominal'},
            {data: 'type'},
            {data: 'sort_number'},            
            {data: 'active', orderable: false, searchable: false},            
            {data: 'action', name: 'Action', orderable: false, searchable: false}
        ]
    });
});
</script>
@else
<script type="text/javascript">
$(function() {
    $('#producttabel').DataTable({
        processing: true,
        serverSide: true,
        "pageLength": 100,
        ajax: "{{ route('product-mlm.yajra.getDatas') }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'provider_code', name: 'Provider Code'},
            {data: 'product_code', name: 'Product Code'},
            {data: 'product_name', name: 'Product Name'},
            {data: 'nominal', name: 'Nominal'},
            {data: 'type', name: 'Type Product'},
            {data: 'sort_number', name: 'Short Number'},            
            {data: 'active', name: 'Status', orderable: false, searchable: false},          
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

$(".select_type_product").select2({
  placeholder: "Filter Type Product",
  allowClear: true
});

$(function(){
  $('#producttabel').on('click', 'a.delete', function(){
    var a = $(this).data('value');
    $('#setDelete').attr('href', "{{ url('/') }}/product-mlm/delete/"+a);
  });
});

</script>

@endsection
