@extends('layout.master')

@section('title')
  <title>STS | Sales Deposit Transaction</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/pnotify/dist/pnotify.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/datepicker/datepicker3.css') }}" rel="stylesheet">
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
    <div class="alert {{ Session::get('alert') }} alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
      </button>
      <strong>{{ Session::get('berhasil') }}</strong>
    </div>
  </div>
</div>
@endif

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div id="alert-error" class="alert alert-danger alert-dismissible hidden" >
      <button type="button" class="close" id="close-alert-error"><span>×</span>
      </button>
      <p><strong>error, </strong> server mengalami gangguan </p>   
    </div>
  </div>
</div>

@can('set-belum-setor-sales-deposit-transaction')
<div class="modal fade modal-set-belum-setor" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Set belum Disetorkan</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setBelumSetor">Ya</a>
      </div>
    </div>
  </div>
</div>
@endcan

@can('set-sudah-setor-sales-deposit-transaction')
<div class="modal fade modal-set-setor" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Set Sudah Disetorkan</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setSetor">Ya</a>
      </div>
    </div>
  </div>
</div>
@endcan


<div class="page-title">
  <div class="title_left">
    <h3>All Sales Deposit Transaction <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Sales Deposit Transaction</h2>
        <div class="clearfix"></div>
      </div>

      <div class="x_content table-responsive">
        <form class="form-inline text-center" id="formFilter">
          <div class="form-group text-left">
            <label>Sales Name</label>
            <div class="clearfix"></div>
            <input type="text" name="f_sales_name" class="form-control" placeholder="Sales Name" @if(isset($request->f_sales_name)) value="{{ $request->f_sales_name }}" @endif>  
          </div>

          <div class="form-group text-left">
            <label>Status Setor</label>  
            <div class="clearfix"></div>
            <select name="f_status_setor" class="form-control select_status_setor">
              <option value="">Filter Status Setor</option>
              <option value="R" @if($request->f_status_setor == 'R') selected @endif>Sudah Setor</option>
              <option value="D" @if($request->f_status_setor == 'D') selected @endif>Belum Setor</option>          
            </select>
          </div>

          <div class="form-group text-left">
            <label>Tanggal Mulai</label>  
            <div class="clearfix"></div>
            <input type="text" class="form-control" name="f_start_date" id="f_start_date" @if(isset($request->f_start_date)) value="{{ $request->f_start_date }}" @else value="{{ date('Y-m-d',strtotime("-3 days")) }}" @endif placeholder="yyyy-mm-dd" required="">
          </div>

          <div class="form-group text-left">
            <label>Tanggal Berakhir</label>  
            <div class="clearfix"></div>
            <input type="text" class="form-control" name="f_end_date" id="f_end_date" placeholder="yyyy-mm-dd" @if(isset($request->f_end_date)) value="{{ $request->f_end_date }}" @else value="{{ date('Y-m-d', time()) }}" @endif required="">
          </div>

          <div class="form-group">
            <label>&nbsp;</label>
            <div class="clearfix"></div> 
            <input type="submit" name="search" value="Search" class="btn btn-primary">  
          </div>
          
        </form>
        <div class="ln_solid"></div>
        <table id="sales-deposit-table" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Sales Name</th>
              <th>Agent Name</th>
              <th>Doc No</th>
              <th>Doc Date</th>
              <th>Jumlah Deposit</th>              
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
<script src="{{ asset('amadeo/js/moment/moment.min.js') }}"></script>
<script src="{{asset('amadeo/vendors/datepicker/bootstrap-datepicker.js')}}"></script>


@if(isset($request))
<script type="text/javascript">  
  $(function(){    
      $('#sales-deposit-table').DataTable({
        processing: true,
        serverSide: true,
        "searching": false,
        "pageLength": 100,
        ajax: '{{route('salesDepositTransaction.getDatas')}}?f_sales_name={{ $request->f_sales_name }}&f_status_setor={{$request->f_status_setor}}&f_start_date={{ isset($request->f_start_date) ? $request->f_start_date : date('Y-m-d',strtotime("-3 days")) }}&f_end_date={{ isset($request->f_end_date) ? $request->f_end_date : date('Y-m-d', time()) }}',
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'sales_name'},
            {data: 'agent_name'},
            {data: 'doc_no'},
            {data: 'doc_date'},
            {data: 'total_amount_deposit'},            
            {data: 'status', orderable: false, searchable: false },            
            {data: 'action', orderable: false, searchable: false }
        ]
    });
  });
</script>
@else
<script type="text/javascript">
  $(function(){      
      $('#sales-deposit-table').DataTable({
        processing: true,
        serverSide: true,
        "searching": false,
        "pageLength": 100,
        ajax: '{{route('salesDepositTransaction.getDatas')}}',
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'sales_name'},
            {data: 'agent_name'},
            {data: 'doc_no'},
            {data: 'doc_date'},
            {data: 'total_amount_deposit'},            
            {data: 'status', orderable: false, searchable: false },          
            {data: 'action', orderable: false, searchable: false }
        ]
    });
  });
</script>
@endif

<script type="text/javascript">
$(function() {

    $('#sales-deposit-table tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" class="form-control" style="border:1px solid #ceeae8; width:100%" />' );
    });

    var table = $('#sales-deposit-table').DataTable();
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

    @can('set-sudah-setor-sales-deposit-transaction')
    $(document).on('click', 'a.setor', function(){
      var a = $(this).data('value');
      var b = $(this).data('version');
      $('#setSetor').attr('href', "{{ url('/') }}/sales-deposit-transaction/set-sudah-setor/"+a+"/"+b);
    });
    @endcan

    @can('set-belum-setor-sales-deposit-transaction')    
    $(document).on('click','a.belum-setor', function(){
      var a = $(this).data('value');
      var b = $(this).data('version');
      $('#setBelumSetor').attr('href', "{{ url('/') }}/sales-deposit-transaction/set-belum-setor/"+a+"/"+b);
    });
    @endcan
});

$(".select_status_setor").select2({
  placeholder: "Filter Status Setor",
  allowClear: true
});

$('#f_start_date').datepicker({
    autoclose: true,      
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    format: "yyyy-mm-dd"
  });

$('#f_end_date').datepicker({
    autoclose: true,    
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    format: "yyyy-mm-dd"
  });

</script>

@endsection
