@extends('layout.master')

@section('title')
	<title>STS | Edit Partner Pulsa</title>
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
				<h2>Edit Partner Pulsa<small></small></h2>
				<ul class="nav panel_toolbox">
					<a href="{{ route('partner-product.index') }}" class="btn btn-primary btn-sm">Kembali</a>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<form action="{{ route('partner-product.update', ['id' => $getPartnerProduct->id ]) }}" method="POST" class="form-horizontal form-label-left" novalidate>
					{{ csrf_field() }}
					<div class="item form-group {{ $errors->has('partner_pulsa_id') ? 'has-error' : ''}}">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Partner Pulsa <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select id="partner_pulsa_id" name="partner_pulsa_id" class="form-control select2_single" required="required">
								<option value="">Pilih</option>
								@foreach ($getPartnerPulsa as $key)
									<option value="{{ $key->id }}" {{ old('partner_pulsa_id', $getPartnerProduct->partner_pulsa_id) == $key->id ? 'selected' : '' }}>{{ $key->partner_pulsa_name}}</option>
								@endforeach
							</select>
							@if($errors->has('partner_pulsa_id'))
								<code><span style="color:red; font-size:12px;">{{ $errors->first('partner_pulsa_id')}}</span></code>
							@endif
						</div>
					</div>

					<div class="item form-group {{ $errors->has('provider_id') ? 'has-error' : ''}}">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="provider_id">Provider <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select id="provider_id" name="provider_id" class="form-control select2_single" required="required">
								<option value="">Pilih</option>
								@foreach ($getProvider as $key)
									<option value="{{ $key->id }}" {{ old('provider_id', $getPartnerProduct->provider_id) == $key->id ? 'selected' : '' }}>{{ $key->provider_name}}</option>
								@endforeach
							</select>
							@if($errors->has('provider_id'))
								<code><span style="color:red; font-size:12px;">{{ $errors->first('provider_id')}}</span></code>
							@endif
						</div>
					</div>

					<div class="item form-group {{ $errors->has('product_id') ? 'has-error' : ''}}">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Product <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select id="product_id" name="product_id" class="form-control select2_single" required="required">
								<option value="">Pilih</option>
								@foreach ($getProduct as $key)
									<option value="{{ $key->id }}" {{ old('product_id', $getPartnerProduct->product_id) == $key->id ? 'selected' : '' }}>{{ $key->product_name}}</option>
								@endforeach
							</select>
							@if($errors->has('product_id'))
								<code><span style="color:red; font-size:12px;">{{ $errors->first('product_id')}}</span></code>
							@endif
						</div>
					</div>

					<div class="item form-group {{ $errors->has('partner_product_code') ? 'has-error' : ''}}">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Kode Partner Pulsa</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input id="name" class="form-control col-md-7 col-xs-12" name="partner_product_code" type="text" value="{{ $getPartnerProduct->partner_product_code }}" readonly>
						</div>
					</div>

					<div class="item form-group {{ $errors->has('partner_product_name') ? 'has-error' : ''}}">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="partner_product_name">Nama Partner Pulsa <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input id="partner_product_name" class="form-control" name="partner_product_name" required="required" type="text" value="{{ old('partner_product_name', $getPartnerProduct->partner_product_name) }}">
							@if($errors->has('partner_product_name'))
								<code><span style="color:red; font-size:12px;">{{ $errors->first('partner_product_name')}}</span></code>
							@endif
						</div>
					</div>

					<div class="ln_solid"></div>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Active</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<label>
								<input type="checkbox" class="flat" name="active" {{ $getPartnerProduct->active == 1 ? 'checked="checked"' : '' }}/>
							</label>
						</div>
					</div>
					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="col-md-6 col-md-offset-3">
							<a href="{{ route('partner-product.index') }}" class="btn btn-primary">Cancel</a>
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
	$(".select2_single").select2({
		placeholder: "Choose",
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
