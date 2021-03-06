@extends('layout.master')

@section('title')
  <title>STS | Edit Supplier Pulsa</title>
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
        <h2>Edit Supplier Pulsa<small></small></h2>
        <ul class="nav panel_toolbox">
          <a href="{{ route('partner-pulsa.index') }}" class="btn btn-primary btn-sm">Back</a>
        </ul>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form action="{{ route('partner-pulsa.update', ['id' => $getPartnerPulsa->partner_pulsa_id, 'version' => $getPartnerPulsa->version] ) }}" method="post" class="form-horizontal form-label-left" novalidate>
          {{ csrf_field() }}

          <div class="item form-group {{ $errors->has('partner_pulsa_code') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
              Supplier Pulsa Code <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input
                id="partner_pulsa_id"
                class="form-control col-md-7 col-xs-12"
                name="partner_pulsa_id"
                type="hidden"
                value="{{ $getPartnerPulsa->partner_pulsa_id }}"
                readonly
              >
              <input
                id="version"
                class="form-control col-md-7 col-xs-12"
                name="version"
                type="hidden"
                value="{{ $getPartnerPulsa->version }}"
                readonly
              >
              <input
                id="name"
                class="form-control col-md-7 col-xs-12"
                name="partner_pulsa_code"
                type="text"
                value="{{ old('partner_pulsa_code', $getPartnerPulsa->partner_pulsa_code) }}"
                onchange="this.value = this.value.toUpperCase()"
              >
              @if($errors->has('partner_pulsa_code'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('partner_pulsa_code')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('partner_pulsa_name') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="partner_pulsa_name">
              Supplier Pulsa Name<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input
                id="partner_pulsa_name"
                class="form-control"
                name="partner_pulsa_name"
                required="required"
                type="text"
                value="{{ old('partner_pulsa_name', $getPartnerPulsa->partner_pulsa_name) }}"
              >
              @if($errors->has('partner_pulsa_name'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('partner_pulsa_name')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('description') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">
              Description<span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea
                id="description"
                class="form-control"
                name="description"
                required="required"
              >{{ old('description', $getPartnerPulsa->description) }}</textarea>
              @if($errors->has('description'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('description')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('type_top') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="flg_need_deposit">
              Type TOP <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select
                class="form-control select2_single"
                name="type_top"
                required="required"

              >
                <option value="">Pilih</option>
                @php ($arrTT = array('DEPOSIT','DENOM','TERMIN'))
                @for($i=0; $i<=2; $i++)
                <option
                  value="{{ $arrTT[$i] }}"
                  {{ old('type_top', $getPartnerPulsa->type_top) == $arrTT[$i] ? 'selected' : '' }}
                >
                  {{ $arrTT[$i] }}
                </option>
                @endfor
              </select>
              @if($errors->has('type_top'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('type_top')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('payment_termin') ? 'has-error' : ''}} for-termin" {{ old('type_top', $getPartnerPulsa->type_top) != 'TERMIN' ? 'style=display:none' : ''}}>
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_termin">Payment Termin <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="payment_termin" class="form-control" name="payment_termin" placeholder="E.g: 10" required="required" type="text" value="{{ old('payment_termin') }}" onkeypress="return isNumber(event)" maxlength="9">
              @if($errors->has('payment_termin'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('payment_termin')}}</span></code>
              @endif
            </div>
          </div>

          <div class="item form-group {{ $errors->has('status_pkp') ? 'has-error' : ''}}">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status_pkp">
              Status PKP <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select
                class="form-control select2_single"
                name="status_pkp"
                required="required"
                id="status_pkp"
              >
                <option value="">Pilih</option>
                <option value="Y" {{ old('flg_pkp', $getPartnerPulsa->flg_pkp) == 'Y' ? 'selected' : ''}} >PKP</option>
                <option value="N" {{ old('flg_pkp', $getPartnerPulsa->flg_pkp) == 'N' ? 'selected' : ''}} >Non PKP</option>
              </select>
              @if($errors->has('status_pkp'))
                <code><span style="color:red; font-size:12px;">{{ $errors->first('status_pkp')}}</span></code>
              @endif
            </div>
          </div>

          <div class="ln_solid"></div>

          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="active">Active {{$getPartnerPulsa->active}}</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <label>
                <input id="active" type="checkbox" class="flat" name="active" {{ old('active', $getPartnerPulsa->active) ? 'checked' : '' }}/>
              </label>
            </div>
          </div>

          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-md-offset-3">
              <a href="{{ route('partner-pulsa.index') }}" class="btn btn-primary">Cancel</a>
              @can('update-partner-pulsa')
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
    placeholder: "Choose",
    allowClear: true
  });

  $('select[name=type_top]').change(function() {
    if($(this).val() == 'TERMIN')
    {
      $(".for-termin").show();
    }
    else
    {
      $(".for-termin").hide();
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

  $('#flg_need_deposit').click(function() {
    $("#payment_termin").toggle(this.checked);
  });
</script>
@endsection
