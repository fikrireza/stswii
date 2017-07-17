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

<div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Hapus Data Ini</h4>
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

<div class="page-title">
  <div class="title_left">
    <h3>All Partner Product Purch Price <small></small></h3>
  </div>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Partner Product Purch Price </h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('partner-product-purch-price.tambah') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table id="dataTables" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Partner Product Name</th>
              <th>Gross Purches Price</th>
              <th>Tax Percentage</th>
              <th>Date Time Start</th>
              <th>Date Time End</th>
              <th>Version</th>
              <th>Status</th>
              <th>Created By</th>
              <th>Created Date</th>
              <th>Updated By</th>
              <th>Updated Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php
              $no = 1;
            @endphp
            @foreach ($callDatas as $key)
            <tr>
              <td>{{ $no++ }}</td>
              <td>{{ $key->partnerpulsa->partner_product_name }}</td>
              <td>{{ number_format($key->gross_purch_price, 0, ',', '.') }}</td>
              <td>{{ $key->tax_percentage or '-' }}</td>
              <td>{{ $key->datetime_start }}</td>
              <td>{{ $key->datetime_end }}</td>
              <td>{{ $key->version }}</td>
              <td class="text-center">
                @if ($key->active == 1)
                  <a 
                    href="" 
                    class="unpublish" 
                    data-value="{{ $key->id }}" 
                    data-toggle="modal" 
                    data-target=".modal-nonactive"
                  >
                    <span class="label label-success" data-toggle="tooltip" data-placement="top" title="Active"><i class="fa fa-thumbs-o-up"></i></span>
                  </a>
                  <br>
                  <span class="label label-primary">{{ $key->active_datetime or '-' }}</span>
                @else
                  <a 
                    href="" 
                    class="publish" 
                    data-value="{{ $key->id }}" 
                    data-toggle="modal" 
                    data-target=".modal-active"
                  >
                    <span class="label label-danger" data-toggle="tooltip" data-placement="top" title="NonActive"><i class="fa fa-thumbs-o-down"></i></span>
                  </a>
                  <br>
                  <span class="label label-primary">{{ $key->non_active_datetime or '-' }}</span>
                @endif
              </td>
              <td>{{ $key->createdBy->name or '-' }}</td>
              <td>{{ $key->created_at or '-' }}</td>
              <td>{{ $key->updatedBy->name or '-' }}</td>
              <td>{{ $key->updated_at or '-' }}</td>
              <td>
                <a 
                  class="update" 
                  data-id="{{ $key->id }}" 
                  data-toggle="modal" 
                  data-target=".modal-form-update"
                >
                  <span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-pencil"></i></span>
                </a>
                <a 
                  href="" 
                  class="delete" 
                  data-value="{{ $key->id }}" 
                  data-toggle="modal" 
                  data-target=".modal-delete"
                >
                  <span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-remove"></i></span>
                </a>
              </td>
            </tr>
            @php
              $no++;
            @endphp
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
  $('.unpublish').click(function() {
    var a = $(this).data('value');
    $('#setUnpublish').attr('href', "{{ url('/') }}/partner-product-purch-price/active/"+a);
  });
});
$(function(){
  $('.publish').click(function() {
    var a = $(this).data('value');
    $('#setPublish').attr('href', "{{ url('/') }}/partner-product-purch-price/active/"+a);
  });
});
$(function(){
  $('.delete').click(function() {
    var a = $(this).data('value');
    $('#setDelete').attr('href', "{{ url('/') }}/partner-product-purch-price/delete/"+a);
  });
});
</script>

@endsection
