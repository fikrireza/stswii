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

<div class="row" id="alert-success" style="display: none;">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="alert alert-success alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
      </button>
      <strong></strong>
    </div>
  </div>
</div>

<div class="row" id="alert-danger" style="display: none;">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
      </button>
      <strong></strong>
    </div>
  </div>
</div>

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

<div class="modal fade modal-detail" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content modal-lg">

      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Detail Transaction</h4>
      </div>
      <div class="modal-body">
        <form class="">
          <div class="form-group row">
          <label class="control-label col-md-4 text-right">Agent Name</label>
          <label class="control-label col-md-6">: <span id="agent-name"></span></label>
        </div>
        <div class="form-group row">
          <label class="control-label col-md-4 text-right">Agent Phone</label>
          <label class="control-label col-md-6">: <span id="agent-phone"></span></label>
        </div>
        <div class="form-group row">
          <label class="control-label col-md-4 text-right">Transaction Date</label>
          <label class="control-label col-md-6">: <span id="transaction-date"></span></label>
        </div>
        <div class="form-group row">
          <label class="control-label col-md-4 text-right">Ordered Product Code</label>
          <label class="control-label col-md-6">: <span id="ordered-product-code"></span></label>
        </div>
        <div class="form-group row">
          <label class="control-label col-md-4 text-right">Product Price</label>
          <label class="control-label col-md-6">: Rp. <span id="product-price"></span></label>
        </div> 
        <div class="form-group row">
          <label class="control-label col-md-4 text-right">Destination Phone</label>
          <label class="control-label col-md-6">: <span id="destination-phone"></span></label>
        </div>
        <div class="form-group row">
          <label class="control-label col-md-4 text-right">Partner Product Code</label>
          <label class="control-label col-md-6">: <span id="partner-product-code"></span></label>
        </div>
        <div class="form-group row">
          <label class="control-label col-md-4 text-right">Partner Code</label>
          <label class="control-label col-md-6">: <span id="partner-code"></span></label>
        </div>
        <div class="form-group row">
          <label class="control-label col-md-4 text-right">Transaction Status</label>
          <label class="control-label col-md-6">: <span id="transaction-status"></span></label>
        </div>
        <div class="form-group row">
          <label class="control-label col-md-4 text-right">Remark</label>
          <label class="control-label col-md-6">: <span id="status-remark"></span></label>
        </div>
        </form>
        <hr>
        <table id="change-status-table" class="table table-striped table-bordered no-footer" width="100%">
            <thead>
              <tr role="row">
                <th>New Status</th>
                <th>User</th>
                <th>Update Time</th>
                <th>Status Remark</th>
                <th>Internal Remark</th>                 
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </tr>
            </tfoot>
          </table>
      </div>

    </div>
  </div>
</div>

<div class="modal fade modal-log" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Log Transaction</h4>
      </div>
      <div class="modal-body table-responsive">
          <table id="log-tabel" class="table table-striped table-bordered no-footer" width="100%">
            <thead>
              <tr role="row">
                <th>Log Time</th>
                <th>Body</th>                
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th></th>
                <th></th>
              </tr>
            </tfoot>
          </table>
      </div>

    </div>
  </div>
</div>

@can('set-gagal-inquiry-pesanan-agent')
<div class="modal fade modal-set-gagal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form method="POST" id="form-set-gagal" class="form-horizontal form-label-left" novalidate>
        <div class="modal-header alert-danger">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Set Gagal</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <input
              id="pos_id"
              name="pos_id"
              type="hidden"
              value="{{ old('pos_id') }}"
            >

            <div id="form-group-remark-user" class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark_agent">
                Remark User <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea
                  id="remark_user"
                  class="form-control col-md-7 col-xs-12"
                  name="remark_user"
                  
                  required="required"
                >{{ old('remark_user') }}</textarea>                
                  <code id="error-massage-remark-user" style="display: none;"><span style="color:red; font-size:12px;"></span></code>
              </div>
            </div>

            <div id="form-group-remark-internal" class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark_internal">
                Remark Internal <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea
                  id="remark_internal"
                  class="form-control col-md-7 col-xs-12"
                  name="remark_internal"
                  
                  required="required"
                >{{ old('remark_internal') }}</textarea>
                <code id="error-massage-remark-internal" style="display: none;"><span style="color:red; font-size:12px;"></span></code>
              </div>
            </div>

            <div id="form-group-password" class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">
                Password <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
                <code id="error-massage-password" style="display: none;"><span style="color:red; font-size:12px;"></span></code>
              </div>
            </div>

        </div>
        <div class="modal-footer">
          <button id="submit-button-gagal" type="submit" class="btn btn-success" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Loading">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endcan

@can('set-gagal-inquiry-pesanan-agent')
<div class="modal fade modal-set-sukses" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="form-set-sukses" id="form-set-sukses" method="POST" class="form-horizontal form-label-left" novalidate>
        <div class="modal-header alert-success">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Set Sukses</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <input
              id="pos_id"
              name="pos_id"
              type="hidden"
              value="{{ old('pos_id') }}"
            >
            <div id="form-group-remark-user" class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark_agent">
                Remark User <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea
                  id="remark_user"
                  class="form-control col-md-7 col-xs-12"
                  name="remark_user"
                  
                  required="required"
                >{{ old('remark_user') }}</textarea>                
                  <code id="error-massage-remark-user" style="display: none;"><span style="color:red; font-size:12px;"></span></code>
              </div>
            </div>

            <div id="form-group-remark-internal" class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="remark_internal">
                Remark Internal <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea
                  id="remark_internal"
                  class="form-control col-md-7 col-xs-12"
                  name="remark_internal"
                  
                  required="required"
                >{{ old('remark_internal') }}</textarea>
                <code id="error-massage-remark-internal" style="display: none;"><span style="color:red; font-size:12px;"></span></code>
              </div>
            </div>

            <div id="form-group-password" class="item form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">
                Password <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
                <code id="error-massage-password" style="display: none;"><span style="color:red; font-size:12px;"></span></code>
              </div>
            </div>
            
        </div>
        <div class="modal-footer">
          <button id="submit-button-sukses" type="submit" class="btn btn-success" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Loading">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endcan

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
            <label>Receiver Phone</label>
            <div class="clearfix"></div>
            <input type="text" name="f_receiver_phone" class="form-control" placeholder="Receiver Phone" @if(isset($request->f_receiver_phone)) value="{{ $request->f_receiver_phone }}" @endif>  
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
          <div class="clearfix"></div>
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
              <th>Transaction Date</th>
              <th>Ordered Product Code</th>
              <th>Receiver Phone</th>
              <th>Partner Product Code</th>
              <th>Transaction Status</th>
              @if(Auth::user()->can('set-sukses-inquiry-pesanan-agent') || Auth::user()->can('set-gagal-inquiry-pesanan-agent'))
              <th>Action Status</th>
              @endif
              <th>Action</th>
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
              @if(Auth::user()->can('set-sukses-inquiry-pesanan-agent') || Auth::user()->can('set-gagal-inquiry-pesanan-agent'))
              <th></th>
              @endif
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
        "pageLength": 100,
        ajax: "{{ route('report.inquiry-pesanan-agent-get-datas') }}?f_agent_name={{ $request->f_agent_name }}&f_agent_phone={{ $request->f_agent_phone }}&f_receiver_phone={{ $request->f_receiver_phone }}&f_transaction_status={{ $request->f_transaction_status }}&f_start_date={{ isset($request->f_start_date) ? $request->f_start_date : date('Y-m-d',strtotime("-1 days")) }}&f_end_date={{ isset($request->f_end_date) ? $request->f_end_date : date('Y-m-d', time()) }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'agent_name'},            
            {data: 'purchase_datetime'},
            {data: 'product_code'},
            {data: 'receiver_phone_number'},
            {data: 'partner_product_code'},
            {data: 'status'},
            @if(Auth::user()->can('set-sukses-inquiry-pesanan-agent') || Auth::user()->can('set-gagal-inquiry-pesanan-agent'))
            {data: 'action_status', orderable: false, searchable: false},
            @endif
            {data: 'action', orderable: false, searchable: false}
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
        ajax: "{{ route('report.inquiry-pesanan-agent-get-datas') }}",
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'agent_name'},            
            {data: 'purchase_datetime'},
            {data: 'product_code'},
            {data: 'receiver_phone_number'},
            {data: 'partner_product_code'},
            {data: 'status'},
            @if(Auth::user()->can('set-sukses-inquiry-pesanan-agent') || Auth::user()->can('set-gagal-inquiry-pesanan-agent'))
            {data: 'action_status', orderable: false, searchable: false},
            @endif
            {data: 'action', orderable: false, searchable: false}
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

  $(function(){
    $('#producttabel').on('click','a.remark', function(){
      /*var agent_name = ;
      var agent_phone = ;
      var transaction_date = ;
      var ordered_product_code = ;
      var product_price = ;
      var destination_phone = ;
      var partner_product_code = ;
      var partner_code = ;
      var transaction_status = ;
      var status_remark = ;    
      $('#remark-status').text(remark);*/
      
      $('#agent-name').text($(this).data('agent-name'));
      $('#agent-phone').text($(this).data('agent-phone'));
      $('#transaction-date').text($(this).data('transaction-date'));
      $('#ordered-product-code').text($(this).data('ordered-product-code'));
      $('#product-price').text($(this).data('product-price'));
      $('#destination-phone').text($(this).data('destination-phone'));
      $('#partner-product-code').text($(this).data('partner-product-code'));
      $('#partner-code').text($(this).data('partner-code'));
      $('#transaction-status').text($(this).data('transaction-status'));
      $('#status-remark').text($(this).data('status-remark'));

      var a = $(this).data('value');        
        $('#change-status-table').DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          "searching": false,          
          ajax: "{{ route('report.inquiry-pesanan-agent-detail-change-status-transaction') }}?pos_id="+a,
          columns: [         
              {data: 'new_status'},                    
              {data: 'name'},
              {data: 'update_status_datetime'},
              {data: 'status_remark'},
              {data: 'internal_remark'}                
          ]
        });  
        $('.modal-detail').modal('show');
      
    });
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

  $(function(){ 

    @can('set-gagal-inquiry-pesanan-agent')
    $(document).on('click', 'a.set-gagal', function(){
      var a = $(this).data('value');
      var b = $(this).data('version');
      $('#form-set-gagal #pos_id').val(a);
      $('#form-set-gagal #version').val(b);
    });
    @endcan

    @can('set-sukses-inquiry-pesanan-agent')
    $(document).on('click','a.set-sukses', function(){
      var a = $(this).data('value');
      var b = $(this).data('version');
      $('#form-set-sukses #pos_id').val(a);
      $('#form-set-sukses #version').val(b);
    });
    @endcan   

    $('#producttabel').on('click', '.detail-log', function(e) {      
        var a = $(this).data('value');        
         $('#log-tabel').DataTable({
          destroy: true,
          processing: true,
          serverSide: true,
          "searching": false,          
          ajax: "{{ route('report.inquiry-pesanan-agent-log') }}?pos_id="+a,
          columns: [         
              {data: 'log_time', orderable: false},                    
              {data: 'body', orderable: false}                      
          ]
        });  
        $('.modal-log').modal('show');         
      });

    $("#form-set-gagal").on('submit', function(event){
      $('code span').text('');
      $('code').hide();
      $('.form-group').removeClass('has-error');
      $('#submit-button-gagal').button('loading');
      event.preventDefault();
      var formData = {
        '_token' : "{{csrf_token()}}",
        'pos_id' : $('#form-set-gagal #pos_id').val(),
        'remark_internal' : $('#form-set-gagal #remark_internal').val(),
        'remark_user' : $('#form-set-gagal #remark_user').val(),
        'password' : $('#form-set-gagal #password').val()
      }
      $.ajax({
       url: 'inquiry-pesanan-agent/set-gagal',
       type: 'POST',
       data: formData,
       dataType: 'json',
       encode : true,
       error: function(response) {
          $('#submit-button-gagal').button('reset');
          if (response.status == 422) {
            if ('remark_user' in response.responseJSON) {
              $('#error-massage-remark-user span').text(response.responseJSON.remark_user);
              $('#error-massage-remark-user').show();
              $('#form-group-remark-user').addClass('has-error');
            }
            if ('remark_internal' in response.responseJSON) {
              $('#error-massage-remark-internal span').text(response.responseJSON.remark_internal);
              $('#error-massage-remark-internal').show();
              $('#form-group-remark-internal').addClass('has-error');
            }
            if ('password' in response.responseJSON) {
              $('#error-massage-password span').text(response.responseJSON.password);
              $('#error-massage-password').show();
              $('#form-group-password').addClass('has-error');
            }
          } 
       },  
       success: function(response) {      
          $('#submit-button-gagal').button('reset');    
          if (response.status == 'OK') {
            $('#alert-success strong').text('Success');
            $('html,body').animate({ scrollTop: 0 }, 'slow');
            $('#alert-success').show(); 
            $('.modal-set-gagal').modal('hide');
           window.setTimeout(function() {
                  $('#alert-success').hide();              
              }, 5000);     
          }else if(response.status == 'FAIL'){

            if (response.errorKey == 'wrong.password') {
              $('#error-massage-password span').text('{{ __('messages.wrong.password') }}');
              $('#error-massage-password').show();
              $('#form-group-password').addClass('has-error');
            }else{
              var errorMessage;
              if (response.errorKey == 'only.success.and.inprogress.transasction.can.change.to.fail.manually') {
                errorMessage = '{{ __('messages.only.success.and.inprogress.transasction.can.change.to.fail.manually') }}';
              }
              else if (response.errorKey == 'transaction.failed') {
                errorMessage = '{{ __('messages.transaction.failed') }}';
              }
              else if (response.errorKey == 'pos.not.found') {
                errorMessage = '{{ __('messages.pos.not.found') }}';
              }
              else if (response.errorKey == 'transaction.failed.cause.has.been.exist') {
                errorMessage = '{{ __('messages.transaction.failed.cause.has.been.exist') }}';
              }
              else if (response.errorKey == 'agent.id.not.found') {
                errorMessage = '{{ __('messages.agent.id.not.found') }}';
              }
              else if (response.errorKey == 'cannnot.reversal.cause.data.not.found') {
                errorMessage = '{{ __('messages.cannnot.reversal.cause.data.not.found') }}';
              }
              else if (response.errorKey == 'error.server') {
                errorMessage = '{{ __('messages.error.server') }}';
              }
              else if (response.errorKey == 'more.than.3.days') {
                errorMessage = '{{ __('messages.more.than.3.days') }}';
              }
              $('#alert-danger').show();
              $('html,body').animate({ scrollTop: 0 }, 'slow');
              $('.modal-set-gagal').modal('hide');              
              $('#alert-danger strong').text(errorMessage);
              window.setTimeout(function() {                
                    $('#alert-danger').hide();
              }, 5000);
            }

          }
       }       
    });
  }); 
  
  $("#form-set-sukses").on('submit', function(event){   
      $('code span').text('');
      $('code').hide();
      $('.form-group').removeClass('has-error');   
      $('#submit-button-sukses').button('loading');
      event.preventDefault();
      var formData = {
        '_token' : "{{csrf_token()}}",
        'pos_id' : $('#form-set-sukses #pos_id').val(),
        'remark_internal' : $('#form-set-sukses #remark_internal').val(),
        'remark_user' : $('#form-set-sukses #remark_user').val(),
        'password' : $('#form-set-sukses #password').val()
      }
      $.ajax({
       url: 'inquiry-pesanan-agent/set-sukses',
       type: 'POST',
       data: formData,
       dataType: 'json',
       encode : true,
       error: function(response) {
        $('#submit-button-sukses').button('reset');
          if (response.status == 422) {
            if ('remark_user' in response.responseJSON) {
              $('#form-set-sukses #error-massage-remark-user span').text(response.responseJSON.remark_user);
              $('#form-set-sukses #error-massage-remark-user').show();
              $('#form-set-sukses #form-group-remark-user').addClass('has-error');
            }
            if ('remark_internal' in response.responseJSON) {
              $('#form-set-sukses #error-massage-remark-internal span').text(response.responseJSON.remark_internal);
              $('#form-set-sukses #error-massage-remark-internal').show();
              $('#form-set-sukses #form-group-remark-internal').addClass('has-error');
            }
            if ('password' in response.responseJSON) {
              $('#form-set-sukses #error-massage-password span').text(response.responseJSON.password);
              $('#form-set-sukses #error-massage-password').show();
              $('#form-set-sukses #form-group-password').addClass('has-error');
            }
          } 
       },  
       success: function(response) {    
          $('#submit-button-sukses').button('reset');      
          if (response.status == 'OK') {
            $('#alert-success strong').text('Success');
            $('html,body').animate({ scrollTop: 0 }, 'slow');
            $('#alert-success').show(); 
            $('.modal-set-sukses').modal('hide');
            window.setTimeout(function() {
                  $('#alert-success').hide();              
              }, 5000);     
          }else if(response.status == 'FAIL'){

            if (response.errorKey == 'wrong.password') {
              $('#form-set-sukses #error-massage-password span').text('{{ __('messages.wrong.password') }}');
              $('#form-set-sukses #error-massage-password').show();
              $('#form-set-sukses #form-group-password').addClass('has-error');
            }else{
              var errorMessage;
              if (response.errorKey == 'only.in.progress.transasction.can.change.to.success.manually') {
                errorMessage = '{{ __('messages.only.in.progress.transasction.can.change.to.success.manually') }}';
              }
              else if (response.errorKey == 'pos.not.found') {
                errorMessage = '{{ __('messages.pos.not.found') }}';
              }
              else if (response.errorKey == 'error.server') {
                errorMessage = '{{ __('messages.error.server') }}';
              }
              else if (response.errorKey == 'more.than.3.days') {
                errorMessage = '{{ __('messages.more.than.3.days') }}';
              }
              $('#alert-danger').show();
              $('html,body').animate({ scrollTop: 0 }, 'slow');
              $('.modal-set-sukses').modal('hide');              
              $('#alert-danger strong').text(errorMessage);
              window.setTimeout(function() {                
                    $('#alert-danger').hide();
              }, 5000);
            }

          }
       }       
    });
  });

    $('.modal-set-gagal').on('hidden.bs.modal', function(){
      $('code span').text('');
      $('code').hide();
      $('.form-group').removeClass('has-error');

      $('#form-set-gagal #remark_internal').val("");
      $('#form-set-gagal #remark_user').val("");
      $('#form-set-gagal #password').val("");
    });

    $('.modal-set-sukses').on('hidden.bs.modal', function(){
      $('code span').text('');
      $('code').hide();
      $('.form-group').removeClass('has-error');
      
      $('#form-set-sukses #remark_internal').val("");
      $('#form-set-sukses #remark_user').val("");
      $('#form-set-sukses #password').val("");
    });

  }); 

</script>

@if(Session::has('set-gagal-false'))
<script>
  $('.modal-set-gagal').modal('show');
</script>
@endif

@if(Session::has('set-sukses-false'))
<script>
  $('.modal-set-sukses').modal('show');
</script>
@endif

@endsection
