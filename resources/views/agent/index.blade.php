@extends('layout.master')

@section('title')
  <title>STS | Agent</title>
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
    <div class="alert alert-danger alert-dismissible alert-error" style="display: none;">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
      </button>
      <p><strong>error, </strong> server mengalami gangguan </p>   
    </div>
  </div>
</div>

@can('update-agent')
<div class="modal fade modal-form-update" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('agent.update') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Update Provider</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <input
              id="agent_id"
              name="agent_id"
              type="hidden"
              value="{{ old('agent_id') }}"
            >
            <input
              id="version"
              name="version"
              type="hidden"
              value="{{ old('version') }}"
            >
            <div class="item form-group {{ $errors->has('address') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">
                Address <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea
                  id="address"
                  class="form-control col-md-7 col-xs-12"
                  name="address"
                  placeholder="Example: Jalan Sudirman"
                  required="required"
                >{{ old('address') }}</textarea>
                @if($errors->has('address'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('address')}}</span></code>
                @endif
              </div>
            </div>

            <div class="item form-group {{ $errors->has('city') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="city">
                City <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input
                  id="city"
                  class="form-control col-md-7 col-xs-12"
                  name="city"
                  placeholder="Example: Jakarta"
                  required="required"
                  type="text"
                  value="{{ old('city') }}"
                >
                @if($errors->has('city'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('city')}}</span></code>
                @endif
              </div>
            </div>
            <code><span style="color:red; font-size:12px;">{{ Session::get('update-false') }}</span></code>
        </div>

        <div class="modal-footer">
          <button id="send" type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endcan

@can('activate-agent')
<div class="modal fade modal-nonactive" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Nonactive Agent</h4>
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
        <h4 class="modal-title" id="myModalLabel2">Activated Agent</h4>
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

<div class="modal fade modal-check-saldo" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Saldo</h4>
      </div>
      <div class="modal-body">
        <h4>Rp. <span id="saldo"></span></h4>
      </div>
      
    </div>
  </div>
</div>

<div class="modal fade modal-reset-pin" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Pin</h4>
      </div>
      <div class="modal-body">
        <h4 id="new-pin"></h4>
      </div>
      
    </div>
  </div>
</div>

<div class="page-title">
  <div class="title_left">
    <h3>All Agent <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Agent </h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table id="agenttable" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Address</th>
              <th>City</th>
              @can('activate-agent')
              <th>Status</th>
              @endcan
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <td></td>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            @can('activate-agent')
            <th></th>
            @endcan
            <td></td>
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
    $('#agenttable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{route('agent.getDatas')}}',
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'agent_name'},
            {data: 'phone_number'},
            {data: 'address'},
            {data: 'city'},
            @can('activate-agent')
            {data: 'active', orderable: false, searchable: false },
            @endcan
            {data: 'action', orderable: false, searchable: false },
        ]
    });

    $('#agenttable tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" class="form-control" style="border:1px solid #ceeae8; width:100%" />' );
    });

    var table = $('#agenttable').DataTable();
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

    @can('update-agent')
    $('#agenttable').on('click', '.update', function(e) {
      var agent_id     = $(this).data('id');
      // var agent_name   = $(this).data('name');
      // var phone_number = $(this).data('phone');
      var address      = $(this).data('address');
      var city         = $(this).data('city');
      var version      = $(this).data('version');
      $("#agent_id").val(agent_id);
      // $("#agent_name").val(agent_name);
      // $("#phone_number").val(phone_number);
      $("#address").val(address);
      $("#city").val(city);
      $("#version").val(version);
    });
    @endcan

    @can('activate-agent')
    $(document).on('click', 'a.publish', function(){
      var a = $(this).data('value');
      var b = $(this).data('version');
      $('#setPublish').attr('href', "{{ url('/') }}/agent/actived/"+a+"/"+b+"/Y");
    });

    $(document).on('click','a.unpublish', function(){
      var a = $(this).data('value');
      var b = $(this).data('version');
      $('#setUnpublish').attr('href', "{{ url('/') }}/agent/actived/"+a+"/"+b+"/N");
    });
    @endcan
});

$(document).on('click','a.check-saldo', function(){
  var a = $(this).data('value');
  $.ajax({
     url: '/agent/checkSaldo/'+a,
     type: 'GET',
     error: function(response) {
        $('.alert-error').show();       
     },  
     success: function(response) {
        if (response.status == 'success') {
          $('#saldo').text(response.amount);
          $('.modal-check-saldo').modal('show');  
        }else{
          $('.alert-error').show(); 
        }    
     }
  });
});

$(document).on('click','a.reset-pin', function(){
  var a = $(this).data('value');
  $.ajax({
     url: '/agent/resetPin/'+a,
     type: 'GET',
     error: function(response) {
        $('.alert-error').show();       
     },  
     success: function(response) {
        if (response.status == 'OK') {
          $('#new-pin').text(response.pin);
          $('.modal-reset-pin').modal('show');  
        }else{
          $('.alert-error').show(); 
        }    
     }
  });
});

</script>

@if(Session::has('update-false'))
<script>
  $('.modal-form-update').modal('show');
</script>
@endif

@endsection
