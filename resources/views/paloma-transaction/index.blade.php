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
<?php
  // @if(Session::has('berhasil'))
  // <script>
  //   window.setTimeout(function() {
  //     $(".alert-success").fadeTo(700, 0).slideUp(700, function(){
  //         $(this).remove();
  //     });
  //   }, 5000);
  // </script>
  // <div class="row">
  //   <div class="col-md-12 col-sm-12 col-xs-12">
  //     <div class="alert alert-success alert-dismissible fade in" role="alert">
  //       <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
  //       </button>
  //       <strong>{{ Session::get('berhasil') }}</strong>
  //     </div>
  //   </div>
  // </div>
  // @endif

  // <div class="modal fade modal-nonactive" tabindex="-1" role="dialog" aria-hidden="true">
  //   <div class="modal-dialog modal-sm">
  //     <div class="modal-content alert-danger">

  //       <div class="modal-header">
  //         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
  //         </button>
  //         <h4 class="modal-title" id="myModalLabel2">Nonactive Product</h4>
  //       </div>
  //       <div class="modal-body">
  //         <h4>Sure ?</h4>
  //       </div>
  //       <div class="modal-footer">
  //         <a class="btn btn-primary disabled" id="setUnpublish">Ya</a>
  //       </div>
  //     </div>
  //   </div>
  // </div>

  // <div class="modal fade modal-active" tabindex="-1" role="dialog" aria-hidden="true">
  //   <div class="modal-dialog modal-sm">
  //     <div class="modal-content">

  //       <div class="modal-header">
  //         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
  //         </button>
  //         <h4 class="modal-title" id="myModalLabel2">Activated Product</h4>
  //       </div>
  //       <div class="modal-body">
  //         <h4>Sure ?</h4>
  //       </div>
  //       <div class="modal-footer">
  //         <a class="btn btn-primary disabled" id="setPublish">Ya</a>
  //       </div>
  //     </div>
  //   </div>
  // </div>

  // <div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  //   <div class="modal-dialog modal-sm">
  //     <div class="modal-content alert-danger">

  //       <div class="modal-header">
  //         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
  //         </button>
  //         <h4 class="modal-title" id="myModalLabel2">Delete Product</h4>
  //       </div>
  //       <div class="modal-body">
  //         <h4>Sure ?</h4>
  //       </div>
  //       <div class="modal-footer">
  //         <a class="btn btn-primary disabled" id="setDelete">Ya</a>
  //       </div>

  //     </div>
  //   </div>
  // </div>
?>

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
        <ul class="nav panel_toolbox">
          <?php
          // <a href="{{ route('product.tambah') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
          ?>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table id="producttabel" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Document Number</th>
              <th>Document Date</th>
              <th>Partner Code</th>
              <th>Deposit Amount</th>
              <th>Status</th>
              <?php /*<th>Action</th>*/ ?>
            </tr>
          </thead>
          <tbody>
            @php
              $no = 1;
            @endphp
            @for ($i=0; $i < 50 ; $i++)
            <tr>
              <td>{{ $no++ }}</td>
              <td>{{ 'Doc'. $i }}</td>
              <td>{{ '01/01/2017 00:00:00' }}</td>
              <td>{{ "Code Partner" }}</td>
              <td>{{ $no . '0,000' }}</td>
              <td class="text-center">@if (Rand(0,1))
                    <a href="" class="unpublish" data-value="1" data-toggle="modal" data-target=".modal-nonactive"><span class="label label-success" data-toggle="tooltip" data-placement="top" title="Active">Active</span></a>
                    <br>
                  @else
                    <a href="" class="publish" data-value="1" data-toggle="modal" data-target=".modal-active"><span class="label label-danger" data-toggle="tooltip" data-placement="top" title="NonActive">Not Active</span></a>
                    <br>
                  @endif
              </td>
              <?php
              // <td>
              //   <a href="{{ route('product.ubah', 1) }}" class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-pencil"></i></a>
              //   <a href="" class="delete" data-value="1" data-toggle="modal" data-target=".modal-delete"><span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-remove"></i></span></a>
              // </td>
              ?>
            </tr>
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
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>

<script type="text/javascript">
  $('#producttabel').DataTable();
  $(function(){
    $('#producttabel').on('click','a.unpublish', function(){
      var a = $(this).data('value');
      $('#setUnpublish').attr('href', "{{ url('/') }}/product/active/"+a);
    });
  });
  $(function(){
    $('#producttabel').on('click', 'a.publish', function(){
      var a = $(this).data('value');
      $('#setPublish').attr('href', "{{ url('/') }}/product/active/"+a);
    });
  });
  $(function(){
    $('#producttabel').on('click', 'a.delete', function(){
      var a = $(this).data('value');
      $('#setDelete').attr('href', "{{ url('/') }}/product/delete/"+a);
    });
  });
</script>
@endsection
