@extends('layout.master')

@section('title')
  <title> | Supplier Product Purch Price</title>
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

@can('activate-partner-product-purch-price')
<div class="modal fade modal-nonactive" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Nonactive Product Purches Price</h4>
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
        <h4 class="modal-title" id="myModalLabel2">Activated Product Purches Price</h4>
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
@can('delete-partner-product-purch-price')
<div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Delete this data</h4>
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
    <h3>All Supplier Product Purchase Price <small></small></h3>
  </div>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Supplier Product Purch Price </h2>
        <ul class="nav panel_toolbox">
          @can('create-partner-product-purch-price')
          <a href="{{ route('partner-product-purch-price.upload') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Upload</a>
          <a href="{{ route('partner-product-purch-price.tambah') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
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
                  {{$list->partner_pulsa_code." / ".$list->partner_pulsa_name}}
                </option>
            @endforeach
          </select>
          <select name="f_active" class="form-control select_status" onchange="this.form.submit()">
            <option value="" @if(isset($request->f_active) && $request->f_active == '') selected @endif>All Status</option>
            <option value="Y" @if(isset($request->f_active) && $request->f_active == 'Y') selected @endif>Active</option>
            <option value="N" @if(isset($request->f_active) && $request->f_active == 'N') selected @endif>Not Active</option>
          </select>
          <input 
            id="f_date" 
            name="f_date" 
            class="f_date form-control" 
            type="text" 
            placeholder="Filter Tanggal" 
            onchange="this.form.submit()"
            @if(isset($request->f_date))
            value="{{ $request->f_date }}" 
            @endif
          >
        </form>
        <div class="ln_solid"></div>

        <table id="dataTables" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Supplier Code</th>
              <th>Supplier Product Code</th>
              <th>Supplier Product Name</th>
              <th>Gross Purches Price</th>
              <th>Use Tax</th>
              <th>Tax Percentage</th>
              <th>Date Time Start</th>
              <th>Date Time End</th>
              @can('activate-partner-product-purch-price')
              <th>Status</th>
              @endcan
              <th>Action</th>
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
              <th></th>
              <th></th>
              <th></th>
              @can('activate-partner-product-purch-price')
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
<script src="{{ asset('amadeo/vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-scroller/js/datatables.scroller.min.js') }}"></script>
<script src="{{ asset('amadeo/js/moment/moment.min.js') }}"></script>
<script src="{{ asset('amadeo/js/datepicker/daterangepicker.js') }}"></script>


@if(isset($request))
<script type="text/javascript">
$(function() {
    $('#dataTables').DataTable({
        processing: true,
        serverSide: true,
        "pageLength": 100,
        ajax: "{{ route('partner-product-purch-price.yajra.getDatas') }}?f_provider={{ $request->f_provider }}&f_partner={{ $request->f_partner }}&f_active={{$request->f_active}}&f_date={{ $request->f_date }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'partner_pulsa_code'},
            {data: 'partner_product_code'},
            {data: 'partner_product_name'},
            {data: 'gross_purch_price'},
            {data: 'flg_tax'},
            {data: 'tax_percentage'},
            {data: 'datetime_start'},
            {data: 'datetime_end'},
            @can('activate-partner-product')
              {data: 'active', name: 'Status', orderable: false, searchable: false},
            @endcan
            {data: 'action', name: 'Action', orderable: false, searchable: false}
        ]
    });

    $('#dataTables tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" class="form-control" style="border:1px solid #ceeae8; width:100%" />' );
    });

    var table = $('#dataTables').DataTable();
    table.columns().every( function () {
        var that = this;
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                .search( this.value )
                .draw();
            }
        });
    });
});
</script>
@else
<script type="text/javascript">
$(function() {
    $('#dataTables').DataTable({
        processing: true,
        serverSide: true,
        "pageLength": 100,
        ajax: "{{ route('partner-product-purch-price.yajra.getDatas') }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'partner_product_code'},
            {data: 'partner_product_name'},
            {data: 'gross_purch_price'},
            {data: 'flg_tax'},
            {data: 'tax_percentage'},
            {data: 'datetime_start'},
            {data: 'datetime_end'},
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

$(".select_partner").select2({
  placeholder: "Filter Supplier",
  allowClear: true
});

$(".select_status").select2({
  placeholder: "Filter Status",
  allowClear: true
});

$('#f_date').daterangepicker({
  "calender_style": "picker_2",
  "singleDatePicker": true,
  "format": 'YYYY-MM-DD',
  "showDropdowns": true,
});


$(function(){
  @can('delete-partner-product-purch-price')
  $(document).on('click','a.delete', function(){
    var a = $(this).data('value');
    var b = $(this).data('version');
    $('#setDelete').attr('href', "{{ url('/') }}/partner-product-purch-price/delete/"+a+"?version="+b);
  });
  @endcan
  @can('activate-partner-product-purch-price')
  $(document).on('click', 'a.publish', function(){
    var a = $(this).data('value');
    var b = $(this).data('version');
    $('#setPublish').attr('href', "{{ url('/') }}/partner-product-purch-price/actived/"+a+"?version="+b);
  });

  $(document).on('click','a.unpublish', function(){
    var a = $(this).data('value');
    var b = $(this).data('version');
    $('#setUnpublish').attr('href', "{{ url('/') }}/partner-product-purch-price/actived/"+a+"?version="+b);
  });
  @endcan
});
</script>

@endsection
