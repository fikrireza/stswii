@extends('layout.master')

@section('title')
  <title></title>
@endsection

@section('headscript')
<link href="{{ asset('backend/vendors/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

<script src="{{ asset('backend/vendors/Chart.js/dist/Chart.min.js')}}"></script>
@endsection


@section('content')

@if(Session::has('firstLogin'))
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
      <strong>{{ Session::get('firstLogin') }}</strong>
    </div>
  </div>
</div>
@endif



@endsection

@section('script')

  <script src="{{ asset('backend/vendors/moment/min/moment.min.js') }}"></script>
  <script src="{{ asset('backend/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

@endsection
