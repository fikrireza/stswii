@extends('layout.master')

@section('title')
  <title>STS | Mutasi Rekening Mandiri </title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
@endsection

@section('content')

@if(Session::has('berhasil'))
<script>
  window.setTimeout(function() {
    $(".alert.alert-success").fadeTo(700, 0).slideUp(700, function(){
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
    $(".alert.alert-danger").fadeTo(700, 0).slideUp(700, function(){
        $(this).remove();
    });
  }, 15000);
</script>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
      </button>
      <strong>{{ __('messages.'.Session::get('gagal')) }}</strong>
    </div>
  </div>
</div>
@endif

<div class="page-title">
  <div class="title_left">
    <h3>All Mutasi Rekening Mandiri <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Mutasi Rekening Mandiri</h2>
 
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <form class="form-inline text-center" id="formFilter">
          <div class="form-group text-left">
            <label>Tanggal</label>
            <div class="clearfix"></div>
            <input id="f_date" name="f_date" class="f_date form-control" type="text" placeholder="Filter Tanggal" @if(isset($request->f_date)) value="{{ $request->f_date }}" @else value="{{ date('d/m/Y', time()) }}" @endif >  
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
              <th>Tanggal Waktu</th>              
              <th>Deskripsi</th>
              <th>Debit</th>
              <th>Kredit</th>
              <th>Saldo</th>
              <th>Unique Code Date</th>
              <th>Unique Code</th>
              <th>Agent Name</th>
              <th>Paloma Member Code</th>              
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th></th>              
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <td></td>
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
        "pageLength": 100,
        ajax: "{{ route('inquiry-mutasi-rekening-mandiri.getDatas') }}?f_date={{ isset($request->f_date) ? $request->f_date : date('d/m/Y', time()) }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'tanggal_waktu'},            
            {data: 'deskripsi'},
            {data: 'debit'},
            {data: 'kredit'},
            {data: 'saldo'},
            {data: 'unique_code_date'},
            {data: 'unique_code'},
            {data: 'agent_name'},
            {data: 'paloma_member_code'}       
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
        "pageLength": 100,
        ajax: "{{ route('inquiry-mutasi-rekening-mandiri.getDatas') }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'tanggal_waktu'},            
            {data: 'deskripsi'},
            {data: 'debit'},
            {data: 'kredit'},
            {data: 'saldo'},
            {data: 'unique_code_date'},
            {data: 'unique_code'},
            {data: 'agent_name'},
            {data: 'paloma_member_code'}
        ]
      });
});
</script>
@endif

<script type="text/javascript">
  $('#f_date').daterangepicker({
    "calender_style": "picker_2",
    "singleDatePicker": true,
    "format": 'DD/MM/YYYY',
    "showDropdowns": true,
  });
 
</script>

@endsection
