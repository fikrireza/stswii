@extends('layout.master')

@section('title')
  <title>STS | Member Order Product</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
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

<div class="page-title">
  <div class="title_left">
    <h3>All Inquiry Pesanan Agent <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Inquiry Pesanan Agent</h2>
 
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <form class="form-inline text-center" id="formFilter">
          <div class="form-group text-left">
            <label>Agent Name</label>
            <div class="clearfix"></div>
            <input type="text" name="f_agent_name" class="form-control" placeholder="Agent Name" @if(isset($request->f_agent_name)) value="{{ $request->f_agent_name }}" @endif>  
          </div>
          <div class="form-group text-left">
            <label>Agent Phone</label>
            <div class="clearfix"></div>
            <input type="text" name="f_agent_phone" class="form-control" placeholder="Agent Phone" @if(isset($request->f_agent_phone)) value="{{ $request->f_agent_phone }}" @endif>  
          </div>
          <div class="form-group text-left">
            <label>Transaction Status</label>
            <div class="clearfix"></div>
            <select class="form-control select_status" name="f_transaction_status">
              <option value="">Filter Status</option>
              <option value="S" @if($request->f_transaction_status == 'S')  selected @endif>Sukses</option>
              <option value="I" @if($request->f_transaction_status == 'I')  selected @endif>Diproses</option>
              <option value="E" @if($request->f_transaction_status == 'E')  selected @endif>Gagal</option>
            </select>  
          </div>
          <div class="form-group text-left">
            <label>Tanggal Mulai</label>
            <div class="clearfix"></div>
            <input id="f_start_date" name="f_start_date" class="f_date form-control" type="text" placeholder="Filter Tanggal Start" @if(isset($request->f_start_date)) value="{{ $request->f_start_date }}" @else value="{{ date('Y-m-d',strtotime("-1 days")) }}" @endif >  
          </div>
          <div class="form-group text-left">
            <label>Tanggal Berakhir</label>
            <div class="clearfix"></div>
            <input id="f_end_date" name="f_end_date" class="f_date form-control" type="text" placeholder="Filter Tanggal End" @if(isset($request->f_end_date)) value="{{ $request->f_end_date }}" @else value="{{ date('Y-m-d', time()) }}" @endif >  
          </div>
          <div class="form-group">
            <label>&nbsp;</label>
            <div class="clearfix"></div> 
            <input type="submit" name="search" value="Search" class="btn btn-primary">  
          </div>
          
        </form>
        <div class="ln_solid"></div>

        <table id="producttabel" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Agent Name</th>
              <th>Agent Phone</th>
              <th>Transaction Date</th>
              <th>Ordered Product Code</th>
              <th>Product Price</th>
              <th>Destination Phone</th>
              <th>Partner Product Code</th>
              <th>Partner Code</th>
              <th>Transaction Status</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
             <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <td></td>
              <th></th>
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
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>
<script src="{{ asset('amadeo/js/moment/moment.min.js') }}"></script>
<script src="{{ asset('amadeo/js/datepicker/daterangepicker.js') }}"></script>

@if(isset($request))
<script type="text/javascript">
$(function() {
    $('#producttabel').DataTable({
        processing: true,
        serverSide: true,
        "searching": false,
        ajax: "{{ route('report.inquiry-pesanan-agent-get-datas') }}?f_agent_name={{ $request->f_agent_name }}&f_agent_phone={{ $request->f_agent_phone }}&f_transaction_status={{ $request->f_transaction_status }}&f_start_date={{ isset($request->f_start_date) ? $request->f_start_date : date('Y-m-d',strtotime("-1 days")) }}&f_end_date={{ isset($request->f_end_date) ? $request->f_end_date : date('Y-m-d', time()) }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'agent_name'},
            {data: 'phone_number'},
            {data: 'purchase_datetime'},
            {data: 'product_code'},
            {data: 'gross_sell_price'},
            {data: 'receiver_phone_number'},
            {data: 'partner_product_code'},
            {data: 'partner_pulsa_code'},
            {data: 'status'}
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
        "searching": false,
        ajax: "{{ route('report.inquiry-pesanan-agent-get-datas') }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'agent_name'},
            {data: 'phone_number'},
            {data: 'purchase_datetime'},
            {data: 'product_code'},
            {data: 'gross_sell_price'},
            {data: 'receiver_phone_number'},
            {data: 'partner_product_code'},
            {data: 'partner_pulsa_code'},
            {data: 'status'}
        ]
    });
});
</script>
@endif

<script type="text/javascript">

  $(".select_status").select2({
    placeholder: "Filter Status",
    allowClear: true
  });

  $('#f_start_date').daterangepicker({
    "calender_style": "picker_2",
    "singleDatePicker": true,
    "format": 'YYYY-MM-DD',
    "showDropdowns": true,
  });

  $('#f_end_date').daterangepicker({
    "calender_style": "picker_2",
    "singleDatePicker": true,
    "format": 'YYYY-MM-DD',
    "showDropdowns": true,
  });

</script>
@endsection
