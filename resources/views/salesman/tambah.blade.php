@extends('layout.master')

@section('title')
  <title>STS | Add Salesman</title>
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


<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Add Salesman<small></small></h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('salesman.index') }}" class="btn btn-primary btn-sm">Back</a>
        </ul>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <form action="{{ route('salesman.store') }}" method="POST" class="form-horizontal form-label-left" novalidate>
          {{ csrf_field() }}
          <div class="item form-group {{ $errors->has('user_id') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="user_id">User</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select class="form-control col-md-7 col-xs-12 select2_single" name="user_id" id="user_id">
                <option value="">Select User</option>
                @foreach($users as $user)
                <option value="{{$user->id}}" @if(old('user_id') == $user->id) selected @endif>{{$user->name}}</option>
                @endforeach
              </select>
              @if($errors->has('user_id'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('user_id')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('limit_deposit') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="limit_deposit">Limit Deposit <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="limit_deposit" class="form-control" name="limit_deposit" placeholder="E.g: 1000000" required="required" type="text" value="{{ old('limit_deposit') }}" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" maxlength="9">
              @if($errors->has('limit_deposit'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('limit_deposit')}}</span></code>
              @endif
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="active">Active</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label>
                <input type="checkbox" class="flat" name="active" id="active" value="Y" {{old('active') == 'Y' ? 'checked' : ''}}/>
              </label>
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <a href="{{ route('salesman.index') }}" class="btn btn-primary">Cancel</a>
              @can('create-salesman')
              <button id="send" type="submit" class="btn btn-success">Submit</button>
              @endcan
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
<script src="{{ asset('amadeo/js/formatNumber.js') }}"></script>


<script>
  $(".select2_single").select2({
    placeholder: "Choose User",
    allowClear: true
  });
</script>
@endsection
