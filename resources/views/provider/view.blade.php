<div class="item form-group">
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="code">
      Provider Code :
    </label>
    <label id="provider-prefix-code" class="col-md-3 col-sm-3 col-xs-12">
    	{{ $getProvider->provider_code }}
    </label>
    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
      Provider Name :
    </label>
    <label id="provider-prefix-name" class="col-md-3 col-sm-3 col-xs-12">
    	{{ $getProvider->provider_name }}
    </label>
</div>
<div id="provider-prefix-number" class="item form-group col-md-12 col-sm-12 col-xs-12" style="border-top: solid 1px rgb(229,229,229);">
	<label for="prefix">
	  Provider Prefix 
	</label>
</div>
@foreach($getProviderPrefix as $list)
<div class='form-group col-md-12 col-sm-12 col-xs-12'>
	<label class='col-md-3 col-sm-3 col-xs-6'>{{ $list->prefix }}</label>
	<label class='col-md-3 col-sm-3 col-xs-6'>
		<a 
			class='update-prefix' 
			data-provider_id='{{ $list->provider_id }}' 
			data-prefix_id='{{ $list->provider_prefix_id }}' 
			data-prefix='{{ $list->prefix }}' 
			data-version='{{ $list->version }}' 
			data-toggle='modal' 
			data-target='.modal-form-update-prefix'
		>
			<span class='btn btn-xs btn-warning btn-sm' data-toggle='tooltip' data-placement='top' title='Update'>
				<i class='fa fa-pencil'></i>
			</span>
		</a>
	</label>
	<label class='col-md-3 col-sm-3 col-xs-6'>
		<a 
			href='' 
			class='delete-prefix' 
			data-value='{{ $list->provider_prefix_id }}' 
			data-version='{{ $list->version }}' 
			data-toggle='modal' 
			data-target='.modal-delete-prefix'
		>
			<span class='btn btn-xs btn-danger btn-sm' data-toggle='tooltip' data-placement='top' title='Hapus'>
				<i class='fa fa-remove'></i>
			</span>
		</a>
	</label>
</div>
@endforeach
<div class="clearfix"></div>