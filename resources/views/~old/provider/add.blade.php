@extends('layout.master')

@section('title')
  <title> | Add Provider</title>
@endsection

@section('headscript')
@endsection

@section('content')
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Add Provider<small></small></h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('ProviderController.index') }}" class="btn btn-primary btn-sm">Back</a>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form action="{{ route('ProviderController.store') }}" method="POST" class="form-horizontal form-label-left" enctype="multipart/form-data" novalidate>
          {{ csrf_field() }}
          <div class="item form-group {{ $errors->has('nama_provider') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Provider <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="name" class="form-control col-md-7 col-xs-12" name="nama_provider" placeholder="Contoh: Nama Provider" required="required" type="text" value="{{ old('nama_provider') }}">
              @if($errors->has('nama_provider'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('nama_provider')}}</span></code>
              @endif
            </div>
          </div>
          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <a href="{{ route('ProviderController.index') }}" class="btn btn-primary">Cancel</a>
              <button id="send" type="submit" class="btn btn-success" disabled="true">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
@endsection
