@extends('layout.master')

@section('title')
  <title>STS | Sales Deposit Transaction Detail</title>
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


<div class="page-title">
  <div class="title_left">
    <h3>All Sales Deposit Transaction Detail <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Sales Deposit Transaction Detail</h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('salesDepositTransaction.index') }}" class="btn btn-primary btn-sm">Back</a>
        </ul>
        <div class="clearfix"></div>
      </div>      
      <div class="x_content table-responsive">

      <div class="form-group row">
        <label class="col-md-2">Salesman</label>
        <label class="col-md-3">: {{$salesDepositTransaction->name}}</label>
      </div>
      <div class="form-group row">
        <label class="col-md-2">Doc No</label>
        <label class="col-md-3">: {{$salesDepositTransaction->doc_no}}</label>
      </div>
      <div class="form-group row">
        <label class="col-md-2">Doc Date</label>
        <label class="col-md-3">: {{ date('Y-m-d', strtotime($salesDepositTransaction->doc_date)) }}</label>
      </div>
      <div class="form-group row">
        <label class="col-md-2">Jumlah Deposit</label>
        <label class="col-md-3">: Rp. {{number_format($salesDepositTransaction->total_amount_deposit, 2)}}</label>
      </div>
      <div class="form-group row">
        <label class="col-md-2">Status</label>
        @if($salesDepositTransaction->status == 'R')
          <label class="col-md-3">: <span class="text-success">Sudah Disetorkan</span></label>
        @endif
        @if($salesDepositTransaction->status == 'D')
          <label class="col-md-3">: <span class="text-danger">Belum Disetorkan</span></label>
        @endif
      </div>
      <hr>
        <table id="sales-deposit-table" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>              
              <th>Agent Name</th>
              <th>Phone Number</th>
              <th>Deposit</th>              
            </tr>
          </thead>
          <tfoot>
            <td></td>            
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
<script src="{{ asset('amadeo/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-scroller/js/datatables.scroller.min.js') }}"></script>

<script type="text/javascript">
$(function() {
    $('#sales-deposit-table').DataTable({
        processing: true,
        serverSide: true,
        "pageLength": 100,
        ajax: '{{route('salesDepositTransaction.getDetailList', ['id' => $salesDepositTransaction->sales_deposit_transaction_id])}}',
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},            
            {data: 'agent_name'},
            {data: 'phone_number'},            
            {data: 'amount_deposit'},
        ]
    });

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

});

</script>

@endsection
