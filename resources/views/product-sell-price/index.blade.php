@extends('layout.master')

@section('title')
  <title>STS | Product Sell Price</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
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

@can('activate-product-sell-price')
<div class="modal fade modal-nonactive" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Nonactive Product Sell Price</h4>
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
        <h4 class="modal-title" id="myModalLabel2">Activated Product Sell Price</h4>
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

@can('delete-product-sell-price')
<div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Delete Product Sell Price</h4>
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
    <h3>All Product Sell Price <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Product Sell Price </h2>
        <ul class="nav panel_toolbox">
          @can('create-product-sell-price')
          <a href="{{ route('product-sell-price.upload') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Upload</a>
          <a href="{{ route('product-sell-price.tambah') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
          @endcan
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <form class="form-inline text-center">
          <select name="f_provider" class="form-control" onchange="this.form.submit()">
            <option value="">Filter Provider</option>
            @foreach($provider as $list)
                <option value="{{$list->provider_id}}" @if($request->f_provider == $list->provider_id) selected @endif>{{$list->provider_name}}</option>
            @endforeach
          </select>
          <select name="f_active" class="form-control" onchange="this.form.submit()">
            <option value="1" @if(isset($request->f_active) && $request->f_active == 1) selected @endif>Active</option>
            <option value="0" @if(isset($request->f_active) && $request->f_active == 0) selected @endif>Not Active</option>
          </select>
        </form>
        <div class="ln_solid"></div>

        <table id="producttabel" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Product Name</th>
              <th>Gross Sell Price</th>
              <th>Use Tax</th>
              <th>Tax Percentage</th>
              <th>Date Time Start</th>
              <th>Date Time End</th>
              @can('activate-product-sell-price')
              <th>Status</th>
              @endcan
              <th>Action</th>
            </tr>
          </thead>
          {{--
          <tbody>
            @php
              $count=1;
            @endphp
            @foreach ($index as $list)
            <tr>
              <td>{{ $count++ }}</td>
              <td>{{ $list->product->product_name }} - Rp. {{ number_format($list->product->nominal) }}</td>
              <td>Rp. {{ number_format($list->gross_sell_price) }}</td>
              <td>{{ $list->flg_tax ? 'Y' : 'N' }}</td>
              <td>{{ $list->tax_percentage }} %</td>
              <td>{{ date('d-m-Y H:i:s', strtotime($list->datetime_start))   }}</td>
              <td>{{ date('d-m-Y H:i:s', strtotime($list->datetime_end)) }}</td>
              @can('activate-product-sell-price')
              <td class="text-center">@if($list->active)
                    <a
                      href=""
                      class="unpublish"
                      data-value="{{ $list->product_sell_price_id }}"
                      data-version="{{ $list->version }}"
                      data-toggle="modal"
                      data-target=".modal-nonactive"
                    >
                      <span class="label label-success" data-toggle="tooltip" data-placement="top" title="Active">Active</span>
                    </a>
                  @else
                    <a href="" class="publish" data-value="{{ $list->product_sell_price_id }}" data-version="{{ $list->version }}" data-toggle="modal" data-target=".modal-active"><span class="label label-danger" data-toggle="tooltip" data-placement="top" title="NonActive">Not Active</span></a>
                  @endif
              </td>
              @endcan
              <td>
                @can('update-product-sell-price')
                <a href="{{ route('product-sell-price.ubah', $list->product_sell_price_id) }}" class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-pencil"></i></a>
                @endcan
                @can('delete-product-sell-price')
                <a href="" class="delete" data-value="{{ $list->product_sell_price_id }}" data-toggle="modal" data-target=".modal-delete"><span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-remove"></i></span></a>
                @endcan
              </td>
            </tr>
            @endforeach
          </tbody>
          --}}
          <tfoot>
            <tr>
              <td></td>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              @can('activate-product-sell-price')
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
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>

@if(isset($request))
<script type="text/javascript">
$(function() {
    $('#producttabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('product-sell-price.yajra.getDatas') }}?f_provider={{ $request->f_provider }}&f_active={{ $request->f_active }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'product_name'},
            {data: 'gross_sell_price'},
            {data: 'flg_tax'},
            {data: 'tax_percentage'},
            {data: 'datetime_start'},
            {data: 'datetime_end'},
            @can('activate-product')
              {data: 'active', name: 'Status', orderable: false, searchable: false},
            @endcan
            {data: 'action', name: 'Action', orderable: false, searchable: false}
        ]
    });

    $('#producttabel tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" class="form-control" style="border:1px solid #ceeae8; width:100%" />' );
    });

    var table = $('#producttabel').DataTable();
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
    $('#producttabel').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('product-sell-price.yajra.getDatas') }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'product_name'},
            {data: 'gross_sell_price'},
            {data: 'flg_tax'},
            {data: 'tax_percentage'},
            {data: 'datetime_start'},
            {data: 'datetime_end'},
            @can('activate-product')
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
  $(function(){
    $('#producttabel').on('click','a.unpublish', function(){
      var a = $(this).data('value');
      var b = $(this).data('version');
      $('#setUnpublish').attr('href', "{{ url('/') }}/product-sell-price/active/"+a+"?version="+b);
    });
  });
  $(function(){
    $('#producttabel').on('click', 'a.publish', function(){
      var a = $(this).data('value');
      var b = $(this).data('version');
      $('#setPublish').attr('href', "{{ url('/') }}/product-sell-price/active/"+a+"?version="+b);
    });
  });
  $(function(){
    $('#producttabel').on('click', 'a.delete', function(){
      var a = $(this).data('value');
      $('#setDelete').attr('href', "{{ url('/') }}/product-sell-price/delete/"+a);
    });
  });
</script>
@endsection
