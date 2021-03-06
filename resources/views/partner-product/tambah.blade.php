@extends('layout.master')

@section('title')
	<title>STS | Add Supplier Product</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Add Supplier Product<small></small></h2>
				<ul class="nav panel_toolbox">
					<a href="{{ route('partner-product.index') }}" class="btn btn-primary btn-sm">Back</a>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<form action="{{ route('partner-product.store') }}" method="post" class="form-horizontal form-label-left" novalidate>
					{{ csrf_field() }}

					<div class="item form-group {{ $errors->has('partner_pulsa_id') ? 'has-error' : ''}}">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="partner_pulsa_id">
							Supplier Pulsa <span class="required">*</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select
								id="partner_pulsa_id"
								name="partner_pulsa_id"
								class="form-control select2_single"
								required="required"
							>
								<option value="">Choose Supplier Pulsa</option>
				                @foreach ($getPartner as $key)
								<option
									value="{{ $key->partner_pulsa_id }}" {{ old('partner_pulsa_id') == $key->partner_pulsa_id ? 'selected' : ''}}
								>
									{{ $key->partner_pulsa_name.'('.$key->partner_pulsa_code.')' }}
								</option>
								@endforeach
							</select>
							@if($errors->has('partner_pulsa_id'))
								<code><span style="color:red; font-size:12px;">{{ $errors->first('partner_pulsa_id')}}</span></code>
							@endif
						</div>
					</div>

					<div class="item form-group {{ $errors->has('provider_id') ? 'has-error' : ''}}">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="provider_id">
							Provider <span class="required">*</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select
								id="provider_id"
								name="provider_id"
								class="form-control select2_single"
								required="required"
							>
								<option value="">Choose Provider</option>
                				@foreach ($getProvider as $key)
								<option
									value="{{ $key->provider_id }}" {{ old('provider_id') == $key->provider_id ? 'selected' : ''}}
								>
									{{ $key->provider_name.'('.$key->provider_code.')' }}
								</option>
								@endforeach
							</select>
							@if($errors->has('provider_id'))
								<code><span style="color:red; font-size:12px;">{{ $errors->first('provider_id')}}</span></code>
							@endif
						</div>
					</div>

					<div class="item form-group {{ $errors->has('product_id') ? 'has-error' : ''}}">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="product_id">
							Product <span class="required">*</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<select
								id="product_id"
								name="product_id"
								class="form-control select2_single"
								required="required"
							>
								<option value="">Choose Product Supplier</option>
							</select>
							@if($errors->has('product_id'))
								<code><span style="color:red; font-size:12px;">{{ $errors->first('product_id')}}</span></code>
							@endif
						</div>
					</div>

					<div class="item form-group {{ $errors->has('partner_product_code') ? 'has-error' : ''}}">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
							Supplier Product Code<span class="required">*</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input
								id="name"
								class="form-control col-md-7 col-xs-12"
								name="partner_product_code"
								type="text"
								value=""
								onchange="this.value = this.value.toUpperCase()"
							>
							@if($errors->has('partner_product_code'))
								<code><span style="color:red; font-size:12px;">{{ $errors->first('partner_product_code')}}</span></code>
							@endif
						</div>
					</div>

					<div class="item form-group {{ $errors->has('partner_product_name') ? 'has-error' : ''}}">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="partner_product_name">
							Supplier Product Name<span class="required">*</span>
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input
								id="partner_product_name"
								class="form-control"
								name="partner_product_name"
								required="required"
								type="text"
								value="{{ old('partner_product_name') }}"
								onchange="this.value = this.value.toUpperCase()"
							>
							@if($errors->has('partner_product_name'))
								<code><span style="color:red; font-size:12px;">{{ $errors->first('partner_product_name')}}</span></code>
							@endif
						</div>
					</div>

					<div class="ln_solid"></div>
					
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="active">Active</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<label>
								<input id="active" type="checkbox" class="flat" name="active" value="Y" {{ old('active') == 'Y' ? 'checked' : '' }}/>
							</label>
						</div>
					</div>

					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="col-md-6 col-md-offset-3">
							<a href="{{ route('partner-product.index') }}" class="btn btn-primary">Cancel</a>
							@can('create-partner-product')
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

<script>
$(".select2_single").select2({
	// placeholder: "Binding from references table and depedencies",
	allowClear: true
});

$(document).ready(function(){
	var old = {{ old('product_id') != '' ? old('product_id') : 0 }};
	$('select#product_id').prop("disabled", true);
	if($('select#provider_id').val() == ''){
		$('select#product_id').val('').trigger('change');
		$('select#product_id').prop("disabled", true);
	}
	else{
		// $('select#product_id').val('').trigger('change');
		$('select#product_id').prop("disabled", false);
		$.getJSON({url: "{{ route('partner-product.ajaxGetProductList') }}/" + $('select#provider_id').val(), success: function(result){
				$('select#product_id').empty();
				$('select#product_id').append("<option value=''>Select Product</option>");
				console.log(result)
				$.each(result, function(i, field){
					if (old == field.product_id) 
					{
						$('#product_id').append("<option value='"+ field.product_id +"' selected>"+ field.product_name +"("+field.product_code+")"+"</option>");
						$('select#product_id').val(old).trigger('change');

					}
					else
					{
						$('#product_id').append("<option value='"+ field.product_id +"'>"+ field.product_name +"("+field.product_code+")"+"</option>");
					}
					
				});
			}
		});
	}
});

$(document).on('change','select#provider_id', function(){
	if($(this).val() == ''){
		$('select#product_id').val('').trigger('change');
		$('select#product_id').prop("disabled", true);
	}
	else{
		$('select#product_id').val('').trigger('change');
		$('select#product_id').prop("disabled", false);
		$.getJSON({url: "{{ route('partner-product.ajaxGetProductList') }}/" + $(this).val(), success: function(result){
				$('select#product_id').empty();
				$('select#product_id').append("<option value=''>Select Product</option>");
				console.log(result)
				$.each(result, function(i, field){
					$('#product_id').append("<option value='"+ field.product_id +"'>"+ field.product_name +"("+field.product_code+")"+"</option>");
				});
			}
		});
	}
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
