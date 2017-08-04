@extends('layout.master')

@section('title')
	<title>STS | Upload Product Sell Price</title>
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
		<h3>Upload Product Sell Price <small></small></h3>
	</div>
</div>

<div class="clearfix"></div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Product Sell Price </h2>
				<ul class="nav panel_toolbox">
					@can('create-product-sell-price')
					<a href="{{ route('product-sell-price.template') }}" class="btn btn-primary btn-sm"> Download Template</a>
					@endcan
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<form class="form-horizontal form-label-left" action="{{ route('product-sell-price.prosesTemplate') }}" method="post" enctype="multipart/form-data">
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
							<a href="{{ route('product-sell-price.index') }}" class="btn btn-primary">Cancel</a>
							@can('create-product-sell-price')
							<button id="send" type="submit" class="btn btn-success">Process</button>
							@endcan
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@if (isset($collect))
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>Check</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content table-responsive">
				<form class="form-horizontal form-label-left" action="{{ route('product-sell-price.storeTemplate') }}" method="post">
				{{ csrf_field() }}
				<table class="table table-striped table-bordered no-footer tablecheck" width="100%" id="">
					<thead>
						<th>Delete</th>
						<th>Product</th>
						<th>Gross Sell Price</th>
						<th>Tax Percentage</th>
						<th>Datetime Start</th>
						<th>Datetime End</th>
						<th>Active</th>
					</thead>

					<tbody>
						@php
							$urut = 0;
						@endphp
						@foreach ($collect as $key)
						<tr>
							<td>
								{{ $urut + 1 }}
								<input type="button" name="delete" value="x" class="btn btn-danger btn-sm btn-delete">
							</td>
							<td>
								@php $empty = 1; @endphp
								@foreach($getProduct as $list)
									@if($list->product_id == $key['product_id'])
										{{$list->product_name}} - Rp. {{ number_format($list->nominal) }}
										<input type="hidden" name="product_id[{{$urut}}]" value="{{$key['product_id']}}">
										@php $empty = 0; @endphp
										@break
									@endif
								@endforeach
								@if($empty)
								<input type="hidden" name="product_id[{{$urut}}]" value="0">
								@endif
							</td>
							<td>
								Rp. {{ number_format($key['gross_sell_price']) }}
								<input type="hidden" name="gross_sell_price[{{$urut}}]" value="{{ $key['gross_sell_price'] }}"/>
							</td>
							<td>
								{{ $key['tax_percentage'] }} %
								<input type="hidden" name="tax_percentage[{{$urut}}]" value="{{ $key['tax_percentage'] }}"/>
							</td>
							<td>
								{{ $key['datetime_start'] }}
								<input type="hidden" name="datetime_start[{{$urut}}]" value="{{ $key['datetime_start'] }}"/>
							</td>
							<td>
								{{ $key['datetime_end'] }}
								<input type="hidden" name="datetime_end[{{$urut}}]" value="{{ $key['datetime_end'] }}"/>
							</td>
							<td>
								<select class="form-control" name="active[{{$urut}}]">
									<option value="1">Active</option>
									<option value="0">Not Active</option>
								</select>
							</td>
						</tr>
						@php
							$urut++
						@endphp
						@endforeach
					</tbody>

				</table>
				<button name="button" class="btn btn-success btn-bg btn-submit" onclick="return false">Upload</button>
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

	$('.btn-submit').click( function() {
        var data = table.$('input, select').serialize();
        window.location = "{{ route('product-sell-price.storeTemplate') }}?"+data;
    } );
</script>


@endsection
