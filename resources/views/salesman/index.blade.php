@extends('layout.master')

@section('title')
  <title>STS | Salesman</title>
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

@can('update-salesman')
<div class="modal fade modal-form-update" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('salesman.update') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Update Salesman</h4>
        </div>
        <div class="modal-body">
            {{ csrf_field() }}
            <input
              id="sales_id"
              name="sales_id"
              type="hidden"
              value="{{ old('salesman_id') }}"
            >
            <input
              id="version"
              name="version"
              type="hidden"
              value="{{ old('version') }}"
            >
            <div class="item form-group {{ $errors->has('limit_deposit') ? 'has-error' : ''}}">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="limit_deposit">
                Limit Deposit <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input
                  id="limit_deposit"
                  class="form-control col-md-7 col-xs-12"
                  name="limit_deposit"
                  placeholder="Example: 1000000"
                  required="required" 
                  onkeydown="return numbersonly(this, event);" 
                  onkeyup="javascript:tandaPemisahTitik(this);" 
                  maxlength="9"
                  value="{{ old('limit_deposit') }}">
                @if($errors->has('limit_deposit'))
                  <code><span style="color:red; font-size:12px;">{{ $errors->first('limit_deposit')}}</span></code>
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

@can('activate-salesman')
<div class="modal fade modal-nonactive" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Nonactive Salesman</h4>
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
        <h4 class="modal-title" id="myModalLabel2">Activated Salesman</h4>
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


<div class="page-title">
  <div class="title_left">
    <h3>All Salesman <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Salesman </h2>
        <ul class="nav panel_toolbox">
          @can('create-product')
          <a href="{{ route('salesman.add') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
          @endcan
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content table-responsive">
        <table id="salesmantable" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Name</th>
              <th>Limit Deposit</th>
              @can('activate-salesman')
              <th>Status</th>
              @endcan              
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <td></td>
            <th></th>
            <th></th>
            @can('activate-salesman')
            <th></th>
            @endcan
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
<script src="{{ asset('amadeo/js/formatNumber.js') }}"></script>

<script type="text/javascript">
$(function() {
    $('#salesmantable').DataTable({
        processing: true,
        serverSide: true,
        "pageLength": 100,
        ajax: '{{route('salesman.getDatas')}}',
        columns: [
            {data: 'slno', name: 'No', orderable: false, searchable: false},
            {data: 'name'},
            {data: 'limit_deposit'},
            @can('activate-salesman')
            {data: 'active', orderable: false, searchable: false },
            @endcan
            {data: 'action', orderable: false, searchable: false },
        ]
    });

    $('#salesmantable tfoot th').each( function () {
      var title = $(this).text();
      $(this).html( '<input type="text" class="form-control" style="border:1px solid #ceeae8; width:100%" />' );
    });

    var table = $('#salesmantable').DataTable();
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

    @can('update-salesman')
    $('#salesmantable').on('click', '.update', function(e) {      
      var sales_id      = $(this).data('sales-id');
      var limit_deposit = tandaPemisahTitik($(this).data('limit-deposit'));
      var version       = $(this).data('version');

      $("#sales_id").val(sales_id);
      $("#limit_deposit").val(limit_deposit);      
      $("#version").val(version);
    });
    @endcan

    @can('activate-salesman')
    $(document).on('click', 'a.publish', function(){
      var a = $(this).data('value');
      var b = $(this).data('version');
      $('#setPublish').attr('href', "{{ url('/') }}/salesman/actived/"+a+"/"+b+"/Y");
    });

    $(document).on('click','a.unpublish', function(){
      var a = $(this).data('value');
      var b = $(this).data('version');
      $('#setUnpublish').attr('href', "{{ url('/') }}/salesman/actived/"+a+"/"+b+"/N");
    });
    @endcan
});

</script>

@if(Session::has('update-false'))
<script>
  $('.modal-form-update').modal('show');
</script>
@endif

@endsection
