@extends('layout.master')

@section('title')
  <title> | Partner Pulsa</title>
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


<div class="modal fade modal-delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Hapus Partner Pulsa</h4>
      </div>
      <div class="modal-body">
        <h4>Yakin ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary disabled" id="setDelete">Ya</a>
      </div>

    </div>
  </div>
</div>

<div class="modal fade modal-nonactive" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content alert-danger">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel2">Nonactive Partner Pulsa</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary disabled" id="setUnpublish">Ya</a>
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
        <h4 class="modal-title" id="myModalLabel2">Activated Partner Pulsa</h4>
      </div>
      <div class="modal-body">
        <h4>Sure ?</h4>
      </div>
      <div class="modal-footer">
        <a class="btn btn-primary disabled" id="setPublish">Ya</a>
      </div>
    </div>
  </div>
</div>

<div class="page-title">
  <div class="title_left">
    <h3>All Partner Pulsa <small></small></h3>
  </div>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Partner Pulsa </h2>
        <ul class="nav panel_toolbox">
          <a class="btn btn-success btn-sm publish" href="{{ route('partner-pulsa.create') }}"><i class="fa fa-plus"></i> Add</a>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table id="dataTables" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Partner Pulsa Code</th>
              <th>Partner Pulsa Name</th>
              <th>Description</th>
              <th>Type TOP</th>
              <th>Payment Termin</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @php
              $no = 1;
              $faker    = Faker\Factory::create();
            @endphp
            @for ($i=0; $i < 15; $i++)
            <tr>
              <td>{{ $no }}</td>
              <td>Partner.{{ rand(10,99) }}</td>
              <td>{{$faker->name}}</td>
              <td>Description Partner Pulsa</td>
              <td>@if($i%3 == 0) {{'DEPOSIT'}} @elseif($i%2 == 1) {{'DENOM'}} @else {{'TERMIN'}}@endif</td>
              <td>{{ $i%2 ? '0' : '1' }}</td>
              <td class="text-center">@if($i%2)
                    <a href="" class="unpublish" data-value="" data-toggle="modal" data-target=".modal-nonactive"><span class="label label-success" data-toggle="tooltip" data-placement="top" title="Active">Active</span></a>
                    <br>
                  @else
                    <a href="" class="publish" data-value="" data-toggle="modal" data-target=".modal-active"><span class="label label-danger" data-toggle="tooltip" data-placement="top" title="NonActive">Inactive</span></a>
                    <br>
                  @endif
              </td>
              <td>
                <a class="update" href="{{ route('partner-pulsa.edit', ['id' => $i])}}"><span class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Update"><i class="fa fa-pencil"></i></span></a>

                <a href="" class="delete" data-value="" data-toggle="modal" data-target=".modal-delete"><span class="btn btn-xs btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-remove"></i></span></a>
              </td>
            </tr>
            @php
              $no++;
            @endphp
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
<script type="text/javascript">
$('#dataTables').DataTable();

$(function(){
  $('#dataTables').on('click', 'a.delete', function(){
    var a = $(this).data('value');
    // $('#setDelete').attr('href', "{{ url('/') }}/partner-?pulsa/delete/"+a);
  });
});

$(function(){
  $('#dataTables').on('click','a.unpublish', function(){
    var a = $(this).data('value');
    // $('#setUnpublish').attr('href', "{{ url('/') }}/partner-pulsa/active/"+a);
  });
});

$(function(){
  $('#dataTables').on('click', 'a.publish', function(){
    var a = $(this).data('value');
    $('#setPublish').attr('href', "{{ url('/') }}/partner-pulsa/active/"+a);
  });
});

</script>
@endsection
