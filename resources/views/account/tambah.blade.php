@extends('layout.master')

@section('title')
  <title>STS | Add Account</title>
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


<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Add Account<small></small></h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('account.index') }}" class="btn btn-primary btn-sm">Kembali</a>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form action="" method="POST" class="form-horizontal form-label-left" novalidate>
          {{ csrf_field() }}
          <div class="item form-group {{ $errors->has('username') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Username</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="name" class="form-control col-md-7 col-xs-12" name="username" type="text" value="{{ old('username') }}">
            </div>
          </div>
          <div class="item form-group {{ $errors->has('email') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Email <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="name" class="form-control" name="email" placeholder="E.g: " required="required" type="text" value="{{ old('email') }}">
              @if($errors->has('email'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('email')}}</span></code>
              @endif
            </div>
          </div>
          <div class="item form-group {{ $errors->has('avatar') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">avatar <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="name" class="form-control" name="avatar" required="required" type="file" value="{{ old('avatar') }}" accept=".jpg,.bmp,.png">
              @if($errors->has('avatar'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('avatar')}}</span></code>
              @endif
            </div>
          </div>
          <div class="ln_solid"></div>
          <div class="item form-group {{ $errors->has('role') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Role <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="role" name="role" class="form-control" required="required" multiple>
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
          <div class="item form-group {{ $errors->has('task') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Task <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select id="task" name="task" class="form-control select2" required="required" multiple>
                <option value="">Pilih</option>
                <option value="1">Provider - Read</option>
                <option value="2">Provider - Create</option>
                <option value="3">Provider - Update</option>
                <option value="4">Provider - Delete</option>
                <option value="5">Product - Read</option>
                <option value="6">Product - Create</option>
                <option value="7">Product - Update</option>
                <option value="8">Product - Delete</option>
              </select>
              @if($errors->has('task'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('task')}}</span></code>
              @endif
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Active</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label>
                <input type="checkbox" class="flat" name="active" />
              </label>
            </div>
          </div>
          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <a href="{{ route('product.index') }}" class="btn btn-primary">Cancel</a>
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

<script>
  $("#role").select2({
    placeholder: "Choose Role",
    allowClear: true
  });

  $("#task").select2({
    placeholder: "Choose Task",
    allowClear: true
  });


  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
    }
    return true;
  }
</script>
@endsection
