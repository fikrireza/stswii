@extends('layout.master')

@section('title')
  <title> | Partner Product Purch Price</title>
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
        <a class="btn btn-primary disabled" id="setUnpublish">Ya</a>
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
        <a class="btn btn-primary disabled" id="setPublish">Ya</a>
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
        <h4 class="modal-title" id="myModalLabel2">Delete this data</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary disabled" id="setDelete">Yes</a>
      </div>

    </div>
  </div>
</div>

<div class="page-title">
  <div class="title_left">
    <h3>All Partner Product Purchase Price <small></small></h3>
  </div>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Partner Product Purch Price </h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('partner-product-purch-price.upload') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Upload</a>
          <a href="{{ route('partner-product-purch-price.tambah') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table id="dataTables" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Partner Product Code</th>
              <th>Partner Product Name</th>
              <th>Gross Purches Price</th>
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
              $no = 1;
              $arrProvName = array(
                '', 
                'Telkomsel', 'Telkomsel', 'Telkomsel', 'Telkomsel', 'Telkomsel', 
                'Indosat', 'Indosat', 'Indosat', 'Indosat', 'Indosat', 
                'Ooredo', 'Ooredo', 'Ooredo', 'Ooredo', 'Ooredo', 
                '3', '3', '3', '3', '3'
              );
              $arrPriceSellCOFP = array(
                '', 
                '5', '10', '20', '50', '100', 
                '5', '10', '20', '50', '100', 
                '5', '10', '20', '50', '100', 
                '5', '10', '20', '50', '100'
              );
              $arrPriceSell = array(
                '', 
                '5000', '10000', '20000', '50000', '100000', 
                '5000', '10000', '20000', '50000', '100000', 
                '5000', '10000', '20000', '50000', '100000', 
                '5000', '10000', '20000', '50000', '100000'
              );
            @endphp
            @for ($i=1; $i < 20; $i++)
            <tr>
              <td>{{ $no++ }}</td>
              <td>PP.Cd.{{ rand(10,99) }}</td>
              <td>{{ $arrPriceSellCOFP[$i].'-'.$arrProvName[$i] }}</td>
              <td>{{ $arrPriceSell[$i] }}</td>
              <td>{{ $i%2 ? 'Y' : 'N' }}</td>
              <td>{{ $i%2 ? '10' : '5' }}</td>
              <td>{{ $i%2 ? '2017/01/01 00:00:01' : '2017/12/31 23:59:59' }}</td>
              <td>{{ $i%2 ? '2017/01/01 00:00:01' : '2017/12/31 23:59:59' }}</td>
              <td class="text-center">
                @if ($i%2 == 1)
                  <a href="" class="unpublish" data-value="{{ $i }}" data-toggle="modal" data-target=".modal-nonactive" >
                    <span class="label label-success" data-toggle="tooltip" data-placement="top" title="Active">Active</span>
                  </a>
                  <br>
                @else
                  <a href="" class="publish" data-value="{{ $i }}" data-toggle="modal" data-target=".modal-active">
                    <span class="label label-danger" data-toggle="tooltip" data-placement="top" title="NonActive">Not Active</span>
                  </a>
                  <br>
                @endif
              </td>
              <td>
                <a href="{{ route('partner-product-purch-price.edit', ['id'=> $i]) }}">
                  <span class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-pencil"></i></span>
                </a>
                <a href="" class="delete" data-value="{{ $i }}" data-toggle="modal" data-target=".modal-delete">
                  <span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-remove"></i></span>
                </a>
              </td>
            </tr>
            @php
              $no++;
            @endphp
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
<script type="text/javascript">
$('#dataTables').DataTable();

$(function(){
  $('.unpublish').click(function() {
    var a = $(this).data('value');
    // $('#setUnpublish').attr('href', "{{ url('/') }}/partner-product-purch-price/active/"+a);
  });
});
$(function(){
  $('.publish').click(function() {
    var a = $(this).data('value');
    // $('#setPublish').attr('href', "{{ url('/') }}/partner-product-purch-price/active/"+a);
  });
});
$(function(){
  $('.delete').click(function() {
    var a = $(this).data('value');
    // $('#setDelete').attr('href', "{{ url('/') }}/partner-product-purch-price/delete/"+a);
  });
});
</script>

@endsection
