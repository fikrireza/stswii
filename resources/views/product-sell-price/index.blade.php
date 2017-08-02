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
          <a href="{{ route('product-sell-price.upload') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Upload</a>
          <a href="{{ route('product-sell-price.tambah') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table id="producttabel" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Provider Code</th>
              <th>Product Name</th>
              <th>Gross Sell Price</th>
              <th>Use Tax</th>
              <th>Tax Percentage</th>
              <th>Date Time Start</th>
              <th>Date Time End</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php
              $arrProvCode = array(
                '', 
                'Prov-01', 'Prov-01', 'Prov-01', 'Prov-01', 'Prov-01', 
                'Prov-02', 'Prov-02', 'Prov-02', 'Prov-02', 'Prov-02', 
                'Prov-03', 'Prov-03', 'Prov-03', 'Prov-03', 'Prov-03', 
                'Prov-04', 'Prov-04', 'Prov-04', 'Prov-04', 'Prov-04'
              );
              $arrProvName = array(
                '', 
                'Telkomsel', 'Telkomsel', 'Telkomsel', 'Telkomsel', 'Telkomsel', 
                'Indosat', 'Indosat', 'Indosat', 'Indosat', 'Indosat', 
                'Ooredo', 'Ooredo', 'Ooredo', 'Ooredo', 'Ooredo', 
                '3', '3', '3', '3', '3'
              );
              $arrPriceSell = array(
                '', 
                '5000', '10000', '20000', '50000', '100000', 
                '5000', '10000', '20000', '50000', '100000', 
                '5000', '10000', '20000', '50000', '100000', 
                '5000', '10000', '20000', '50000', '100000'
              );
            @endphp
            @for ($i=1; $i < 20 ; $i++)
            <tr>
              <td>{{ $i }}</td>
              <td>{{ $arrProvCode[$i] }}</td>
              <td>{{ $arrPriceSell[$i].'-'.$arrProvName[$i] }}</td>
              <td>{{ $arrPriceSell[$i] }}</td>
              <td>{{ 'Y' }}</td>
              <td>{{ 10 }}</td>
              <td>{{ '01/01/2017 00:00:00' }}</td>
              <td>{{ '31/12/2017 23:59:59' }}</td>
              <td class="text-center">@if (rand ( 0 , 1 ))
                    <a
                      href=""
                      class="unpublish"
                      data-value="{{ 1 }}"
                      data-toggle="modal"
                      data-target=".modal-nonactive"
                    >
                      <span class="label label-success" data-toggle="tooltip" data-placement="top" title="Active">Active</span>
                    </a>
                  @else
                    <a href="" class="publish" data-value="{{ 1 }}" data-toggle="modal" data-target=".modal-active"><span class="label label-danger" data-toggle="tooltip" data-placement="top" title="NonActive">Inactive</span></a>
                  @endif
              </td>
              <td>
                <a href="{{ route('product-sell-price.ubah', 1) }}" class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-pencil"></i></a>
                <a href="" class="delete" data-value="{{ 1 }}" data-toggle="modal" data-target=".modal-delete"><span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-remove"></i></span></a>
              </td>
            </tr>
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
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>

<script type="text/javascript">
  $('#producttabel').DataTable();
  $(function(){
    $('#producttabel').on('click','a.unpublish', function(){
      var a = $(this).data('value');
      $('#setUnpublish').attr('href', "{{ url('/') }}/product-sell-price/active/"+a);
    });
  });
  $(function(){
    $('#producttabel').on('click', 'a.publish', function(){
      var a = $(this).data('value');
      $('#setPublish').attr('href', "{{ url('/') }}/product-sell-price/active/"+a);
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
