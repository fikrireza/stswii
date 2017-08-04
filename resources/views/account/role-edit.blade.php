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
    <h3>Edit Role Task <small></small></h3>
  </div>
</div>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
  <div class="x_panel">
    <div class="x_title">
      <h2>Edit Access Role Task</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <form action="{{route('account.roleEdit')}}" method="post" class="form-horizontal form-label-left" novalidate>
        {{ csrf_field() }}
      <div class="item form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        <input type="hidden" name="id" value="{{ $getRole->id }}">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Role Name <span class="required">*</span></label>
        <div class="col-md-9 col-sm-9 col-xs-12">
          <input type="text" name="name" class="form-control" value="{{ $getRole->name }}" readonly>
          @if($errors->has('name'))
          <code><span style="color:red; font-size:12px;">{{ $errors->first('name')}}</span></code>
          @endif
        </div>
      </div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[product-read]" {{ in_array('product-read',$can) ? 'checked="checked"' : '' }} value="true"/> Product - Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[product-create]" {{ in_array('product-create',$can) ? 'checked="checked"' : '' }} value="true"/> Product - Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[product-update]" {{ in_array('product-update',$can) ? 'checked="checked"' : '' }} value="true" /> Product - Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[product-delete]" {{ in_array('product-delete',$can) ? 'checked="checked"' : '' }} value="true" /> Product - Delete
          </label>
        </div>
      </div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Provider</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[provider-read]" {{ in_array('provider-read',$can) ? 'checked="checked"' : '' }} value="true" /> Provider - Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[provider-create]" {{ in_array('provider-create',$can) ? 'checked="checked"' : '' }} value="true" /> Provider - Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[provider-update]" {{ in_array('provider-update',$can) ? 'checked="checked"' : '' }} value="true" /> Provider - Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[provider-delete]" {{ in_array('provider-delete',$can) ? 'checked="checked"' : '' }} value="true" /> Provider - Delete
          </label>
        </div>
      </div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Users</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[user-read]" {{ in_array('user-read',$can) ? 'checked="checked"' : '' }} value="true" /> User - Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[user-create]" {{ in_array('user-create',$can) ? 'checked="checked"' : '' }} value="true" /> User - Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[user-update]" {{ in_array('user-update',$can) ? 'checked="checked"' : '' }} value="true" /> User - Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[user-delete]" {{ in_array('user-delete',$can) ? 'checked="checked"' : '' }} value="true" /> User - Delete
          </label>
        </div>
      </div>
      <div class="item form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Roles</label>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <label>
            <input type="checkbox" class="flat" name="permissions[role-read]" {{ in_array('role-read',$can) ? 'checked="checked"' : '' }} value="true" /> Role - Read
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[role-create]" {{ in_array('role-create',$can) ? 'checked="checked"' : '' }} value="true" /> Role - Create
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[role-update]" {{ in_array('role-update',$can) ? 'checked="checked"' : '' }} value="true" /> Role - Update
          </label><br>
          <label>
            <input type="checkbox" class="flat" name="permissions[role-delete]" {{ in_array('role-delete',$can) ? 'checked="checked"' : '' }} value="true" /> Role - Delete
          </label>
        </div>
      </div>
      <div class="ln_solid"></div>
      <div class="form-group">
        <div class="col-md-6 col-md-offset-3">
          <a href="{{ route('account.role') }}" class="btn btn-primary">Cancel</a>
          <button id="send" type="submit" class="btn btn-success">Submit</button>
        </div>
      </div>
      </form>
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
