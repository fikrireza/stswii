@extends('layout.master')

@section('title')
  <title>| 403 Unauthorized</title>
@endsection

@section('content')

<div class="col-md-12">
  <div class="col-middle">
    <div class="text-center text-center">
      <h1 class="error-number">403</h1>
      <h2>Anda tidak berhak melakukan aksi ini. Silahkan hubungi Admin</h2>
      <br><br>
      <a href="{{ URL::previous() }}" class="btn btn-primary">Back</a>
    </div>
  </div>
</div>
@endsection
