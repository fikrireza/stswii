@extends('layout.master')

@section('title')
	<title>STS | Upload Product Sell Price Mlm</title>
@endsection

@section('headscript')
<link href="{{ asset('amadeo/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/pnotify/dist/pnotify.css') }}" rel="stylesheet">
<link href="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.css') }}" rel="stylesheet">
<style type="text/css">
	td .label-hide{
		display:  block !important;
		visibility: visible;
		opacity: 1;
	}
	td .input-hide{
		display:  inline-block !important;
		visibility: visible;
		opacity: 1;
	}
</style>
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
		<h3>Upload Product Sell Price Mlm<small></small></h3>
	</div>
</div>

<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Product Sell Price Mlm</h2>
				<ul class="nav panel_toolbox">
					<a href="{{ route('product-sell-price-mlm.index') }}" class="btn btn-primary btn-sm">Back</a>
					@can('create-product-sell-price')
					<a href="{{ route('product-sell-price-mlm.template') }}" class="btn btn-primary btn-sm"> Download Template</a>
					@endcan
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<form class="form-horizontal form-label-left" action="{{ route('product-sell-price-mlm.storeTemplate') }}" method="post" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="item form-group {{ $errors->has('file') ? 'has-error' : ''}}">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">File Upload <span class="required">*</span></label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<input type="file" name="file" value="" accept=".xls, .xlsx">
							@if($errors->has('file'))
								<code><span style="color:red; font-size:12px;">{{ $errors->first('file')}}</span></code>
							@endif
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-6 col-md-offset-3">
							<a href="{{ route('product-sell-price-mlm.index') }}" class="btn btn-primary">Cancel</a>
							@can('create-product-sell-price')
							<button id="send" type="submit" class="btn btn-success">Upload</button>
							@endcan
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@if (isset($error))
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2 style="color: red;">Data Error</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content table-responsive">
				<form class="form-horizontal form-label-left" action="{{ route('product-sell-price-mlm.storeTemplate') }}" method="post">
				{{ csrf_field() }}
				<table class="table table-striped table-bordered no-footer tablecheck" width="100%" id="">
					<thead>
						<th>Row</th>
						<th>Product Code</th>
						<th>Catalog Price</th>
						<th>Member Price</th>
						<th>Fee DS Amount</th>
						<th>PV</th>
						<th>Datetime Start</th>
						{{-- <th>Datetime End</th> --}}
						{{-- <th>Active</th> --}}
						<th>Message</th>
					</thead>

					<tbody>
						@foreach ($error as $key)
						<tr>
							<td>
								{{$key['row'] + 1}}
							</td>
							<td>
								{{$key['product_code']}}
							</td>
							<td>
								Rp. {{ number_format($key['catalog_price']) }}
							</td>
							<td>
								Rp. {{ number_format($key['member_price']) }}
							</td>
							<td>
								Rp. {{ number_format($key['fee_ds_amount']) }}
							</td>
							<td>
								{{ $key['pv'] }}
							</td>
							<td>
								{{ $key['datetime_start'] }}
							</td>
							{{-- <td>
								{{ $key['datetime_end'] }}
							</td> --}}
							{{-- <td>
								{{ $key['active'] }}
							</td> --}}
							<td>
								{!! $key['message'] !!}
							</td>
						</tr>
						@endforeach
					</tbody>

				</table>
				</form>
			</div>
		</div>
	</div>
</div>
@endif

@if (isset($pass) && $pass != '')
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2 style="color: green">Successfully saved</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content table-responsive">
				<form class="form-horizontal form-label-left" action="{{ route('product-sell-price-mlm.storeTemplate') }}" method="post">
				{{ csrf_field() }}
				<table class="table table-striped table-bordered no-footer tablecheck" width="100%" id="">
					<thead>
						<th>No</th>
						<th>Product Code</th>
						<th>Catalog Price</th>
						<th>Member Price</th>
						<th>Fee DS Amount</th>
						<th>PV</th>
						<th>Datetime Start</th>
						{{-- <th>Datetime End</th> --}}
						<th>Active</th>
					</thead>

					<tbody>
						@foreach ($pass as $key)
						<tr>
							<td>
								{{$key['row'] + 1}}
							</td>
							<td>
								{{$key['product_code']}}
							</td>
							<td>
								Rp. {{ number_format($key['catalog_price']) }}
							</td>
							<td>
								Rp. {{ number_format($key['member_price']) }}
							</td>
							<td>
								Rp. {{ number_format($key['fee_ds_amount']) }}
							</td>
							<td>
								{{ $key['pv'] }}
							</td>
							<td>
								{{ $key['datetime_start'] }}
							</td>
							{{-- <td>
								{{ $key['datetime_end'] }}
							</td> --}}
							<td>
								{{ $key['active'] }}
							</td>
						</tr>
						@endforeach
					</tbody>

				</table>
				</form>
			</div>
		</div>
	</div>
</div>
@endif

@endsection

@section('script')
<script src="{{ asset('amadeo/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/datatables.net-scroller/js/datatables.scroller.min.js') }}"></script>
<script src="{{ asset('amadeo/vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{ asset('amadeo/vendors/iCheck/icheck.min.js')}}"></script>
<script src="{{ asset('amadeo/vendors/switchery/dist/switchery.min.js')}}"></script>
<script src="{{ asset('amadeo/js/moment/moment.min.js') }}"></script>
<script src="{{ asset('amadeo/js/datepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.js') }}"></script>
<script src="{{ asset('amadeo/vendors/pnotify/dist/pnotify.nonblock.js') }}"></script>

<script type="text/javascript">
	$(document).on('change', '.input-text', function(e) {
		var lebel = $(this).val();
		if($(this).hasClass("currency")){
			var lebel = 'Rp. ' + currency($(this).val());
		}
		if($(this).hasClass("percentage")){
			lebel = lebel + '%';
		}
		$(this).prev().html(lebel);
	});

	function isNumber(evt) {
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
					return false;
			}
			return true;
	}

	function DeleteRowFunction(btndel) {
		if (typeof(btndel) == "object") {
			$(btndel).closest("tr").remove();
		} else {
			return false;
		}
	}

	function currency(number)
	{
		var format = '';
		var angkarev = number.toString().split('').reverse().join('');
		for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) format += angkarev.substr(i,3)+',';
		return format.split('',format.length-1).reverse().join('');
	}

	$('.datetime_start').daterangepicker({
		singleDatePicker: true,
		calender_style: "picker_2",
		format: 'YYYY-MM-DD 00:00:00',
		timePicker: true,
		// minDate: new Date(),
	});
	$('.datetime_end').daterangepicker({
		singleDatePicker: true,
		calender_style: "picker_2",
		format: 'YYYY-MM-DD 23:59:59',
		timePicker: true,
		// minDate: new Date(),
	});

	var table = $('.tablecheck').DataTable({
		"aoColumnDefs": [
			{ "bSearchable": false, "aTargets": [ 0 ] }
		]
	});

	$('.tablecheck').on( 'click', '.btn-delete', function () {
	    table
	        .row( $(this).parents('tr') )
	        .remove()
	        .draw();
	} );

</script>


@endsection
