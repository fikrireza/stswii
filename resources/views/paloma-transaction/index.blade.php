@extends('layout.master')

@section('title')
  <title>STS | Paloma Deposit Transaction</title>
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


<div class="modal fade modal-draft" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Confirm Transaction</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary" id="setRead">Ya</a>
      </div>
    </div>
  </div>
</div>



<div class="page-title">
  <div class="title_left">
    <h3>All Deposit Transaction <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Deposit Transaction </h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table id="deposit_trx" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Document Number</th>
              <th>Document Date</th>
              <th>Partner Code</th>
              <th>Deposit Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php
              $no = 1;
            @endphp
            @foreach ($getData as $key)
            <tr>
              <td>{{ $no++ }}</td>
              <td>{{ $key->doc_no }}</td>
              <td>{{ date('Y-m-d H:i:s', strtotime($key->doc_date)) }}</td>
              <td>{{ $key->partner_code }}</td>
              <td style="text-align:right;">Rp. {{ number_format($key->deposit_amount,'0','.','.') }},-</td>
              <td class="text-center">
                  @if($key->status == 'R')
                    <span class="btn btn-success">Confirmed</span>
                  @elseif($key->status == 'D')
                    <a href="" class="draft" data-value="{{ $key->paloma_deposit_trx_id }}" data-version="{{ $key->version }}" data-toggle="modal" data-target=".modal-draft"><span class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Draft">Draft</span></a>
                    <br>
                  @endif
              </td>
            </tr>
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
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>

<script type="text/javascript">
  $('#deposit_trx').DataTable({
    "pageLength": 100
  });

  $(function(){
    $('#deposit_trx').on('click', 'a.draft', function(){
      var a = $(this).data('value');
      var b = $(this).data('version');
      $('#setRead').attr('href', "{{ url('/') }}/paloma-deposit-transaction/"+a+"?version="+b);
    });
  });
</script>
@endsection
