@extends('layout.master')

@section('title')
  <title>STS | Account</title>
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
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Access Role</h2>
        <ul class="nav panel_toolbox">
          @can('create-role')
          <a href="{{ route('account.roleCreate') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add</a>
          @endcan
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content table-responsive">
        <table class="table table-striped table-bordered no-footer" width="100%">
          <thead>
            <tr role="row">
              <th>No</th>
              <th>Role</th>
              <th>Task</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php
              $no = 1 ;
            @endphp
            @foreach ($getRole as $key)
            <tr>
              <td>{{ $no++ }}</td>
              <td>{{ strtoupper($key->name) }}</td>
              @php
                $permissions = $key->permissions;
                ksort($permissions);
              @endphp
              <td>@foreach ($permissions as $task => $value)
                {{ strtoupper($task) }}<br>
              @endforeach</td>
              <td>
                <a href="{{ route('account.roleUbah', ['slug' => $key->slug ]) }}" class="btn btn-xs btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-pencil"></i></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')

@endsection
