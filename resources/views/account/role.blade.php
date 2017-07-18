@extends('layout.master')

@section('title')
  <title>STS | Account</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">
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


<div class="page-title">
  <div class="title_left">
    <h3>All Role Task <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-5 col-sm-5 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Access Role</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form action="" method="POST" class="form-horizontal form-label-left" novalidate>
        <div class="item form-group {{ $errors->has('role') ? 'has-error' : ''}}">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Role <span class="required">*</span></label>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <select id="role" name="role" class="form-control" required="required">
              <option value="">Pilih</option>
              <option value="1">Administrator</option>
              <option value="2">Admin</option>
              <option value="3">Partner</option>
              <option value="4">Finance</option>
            </select>
            @if($errors->has('role'))
            <code><span style="color:red; font-size:12px;">{{ $errors->first('role')}}</span></code>
            @endif
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <label>
              <input type="checkbox" class="flat" name="active" /> Product - Read
            </label>
            <label>
              <input type="checkbox" class="flat" name="active" /> Product - Create
            </label>
            <label>
              <input type="checkbox" class="flat" name="active" /> Product - Update
            </label>
            <label>
              <input type="checkbox" class="flat" name="active" /> Product - Delete
            </label>
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Provider</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <label>
              <input type="checkbox" class="flat" name="active" /> Provider - Read
            </label>
            <label>
              <input type="checkbox" class="flat" name="active" /> Provider - Create
            </label>
            <label>
              <input type="checkbox" class="flat" name="active" /> Provider - Update
            </label>
            <label>
              <input type="checkbox" class="flat" name="active" /> Provider - Delete
            </label>
          </div>
        </div>
        <div class="item form-group">
          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Partner</label>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <label>
              <input type="checkbox" class="flat" name="active" /> Partner - Read
            </label>
            <label>
              <input type="checkbox" class="flat" name="active" /> Partner - Create
            </label>
            <label>
              <input type="checkbox" class="flat" name="active" /> Partner - Update
            </label>
            <label>
              <input type="checkbox" class="flat" name="active" /> Partner - Delete
            </label>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7 col-sm-7 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Access Role</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table id="producttabel" class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Role</th>
              <th>Task</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Administrator</td>
              <td>Product [Create, Read, Update, Delete] <br> Provider [Create, Read, Update, Delete]</td>
              <td>
                <a href="{{ route('account.ubah', 1) }}" class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-pencil"></i></a>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>Finance</td>
              <td>Product [Read]</td>
              <td>
                <a href="{{ route('account.ubah', 2) }}" class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-pencil"></i></a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('amadeo/vendors/validator/validator.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('amadeo/vendors/iCheck/icheck.min.js')}}"></script>
<script src="{{ asset('amadeo/vendors/switchery/dist/switchery.min.js')}}"></script>

<script type="text/javascript">
  $("#role").select2({
    placeholder: "Choose Role",
    allowClear: true
  });
</script>
@endsection
