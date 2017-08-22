@extends('layout.master')

@section('title')
  <title> | Home</title>
@endsection

@section('headscript')
<script src="{{ asset('amadeo/vendors/Chart.js/dist/Chart.min.js')}}"></script>
@endsection

@section('content')

  <div class="row tile_count">
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-phone"></i> Total Provider</span>
      <div class="count blue">{{ count($provider) }}</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-phone"></i> Total Prefix</span>
      <div class="count red">{{ count($providerPrefix) }}</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-beer"></i> Total Product</span>
      <div class="count green">{{ count($product) }}</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-anchor"></i> Total Partner Pulsa</span>
      <div class="count yellow">{{ count($partnerPulsa) }}</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-anchor"></i> Total Partner Product</span>
      <div class="count purple">{{ count($partnerProduct) }}</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-suitcase"></i> Total Agent</span>
      <div class="count green">{{ count($agent) }}</div>
    </div>
  </div>


@endsection

@section('script')
  {{-- <script type="text/javascript">
    window.onload = function () {
      var cVW = document.getElementById("chartVisitorWebsite");
      var lineChartVisitorWebsite = new Chart(cVW, {
        type: 'line',
        data: {
          labels: [
          '2017-07-01','2017-07-02','2017-07-03','2017-07-04',
          '2017-07-05','2017-07-06','2017-07-07','2017-07-08',
          '2017-07-09','2017-07-10','2017-07-11','2017-07-12',
          '2017-07-13','2017-07-14','2017-07-15','2017-07-16',
          '2017-07-17','2017-07-18','2017-07-19','2017-07-20',
          ],
          datasets: [
          {
            label: "simpati - 5000",
            data: [
            '5500','5500','5500','5500',
            '5500','5500','5500','5500',
            '5500','5500','5500','5500',
            '6000','6000','6000','6000',
            '6000','6000','6000','6000',
            ],
            backgroundColor : "rgba(255,20,20,0)",
            borderColor : "rgba(25,0,30,.5)"
          },
          {
            label: "simpati - 10000",
            data: [
            '9500','9500','9500','9500',
            '9500','9500','9500','9500',
            '9500','9500','9500','9500',
            '9500','9500','9500','9500',
            '9500','9500','9500','9500',
            ],
            backgroundColor : "rgba(255,20,20,0)",
            borderColor : "rgba(25,0,150,.5)"
          },
          {
            label: "simpati - 20000",
            data: [
            '18500','18500','18500','18500',
            '18500','18500','18500','18500',
            '18500','18500','18500','18500',
            '18500','18500','18500','18500',
            '18500','18500','18500','18500',
            ],
            backgroundColor : "rgba(255,20,20,0)",
            borderColor : "rgba(125,0,150,.5)"
          },
          {
            label: "simpati - 50000",
            data: [
            '46500','46500','46500','44500',
            '44500','44500','44500','44500',
            '44500','44500','44500','44500',
            '44500','44500','46500','46500',
            '46500','46500','46500','46500',
            ],
            backgroundColor : "rgba(255,20,20,0)",
            borderColor : "rgba(125,110,150,.5)"
          },
          {
            label: "As - 5000",
            data: [
            '4500','4500','4500','4500',
            '4500','4500','5500','5500',
            '5500','5000','5000','5000',
            '5000','5000','5000','5000',
            '5000','5000','5000','5000',
            ],
            backgroundColor : "rgba(255,20,20,0)",
            borderColor : "rgba(255,20,20,.5)"
          },
          {
            label: "As - 10000",
            data: [
            '9500','9500','9500','9500',
            '9500','8500','8500','8500',
            '8500','8500','8500','8500',
            '8500','8500','8500','8500',
            '8500','8500','8500','8500',
            ],
            backgroundColor : "rgba(255,20,20,0)",
            borderColor : "rgba(255,120,20,.5)"
          },
          {
            label: "As - 20000",
            data: [
            '18000','18000','18000','18000',
            '18000','18000','18000','18000',
            '18000','18500','18500','18500',
            '18500','18500','18000','18000',
            '18000','18000','18000','18000',
            ],
            backgroundColor : "rgba(255,20,20,0)",
            borderColor : "rgba(255,120,120,.5)"
          },

          ]
        }
      });
    }
  </script> --}}
@endsection
