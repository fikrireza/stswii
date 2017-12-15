@extends('layout.master')

@section('title')
  <title> | Home</title>
@endsection

@section('headscript')
<script src="{{ asset('amadeo/vendors/Chart.js/dist/Chart.min.js')}}"></script>
<script src="{{ asset('amadeo/js/highchart/code/highcharts.js')}}"></script>
<script src="{{ asset('amadeo/js/highchart/code/modules/exporting.js')}}"></script>
@endsection

@section('content')

  <div class="row tile_count">
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-phone"></i> Total Provider</span>
      <div class="count blue">{{ $provider }}</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-phone"></i> Total Prefix</span>
      <div class="count red">{{ $providerPrefix }}</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-beer"></i> Total Product</span>
      <div class="count green">{{ $product }}</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-anchor"></i> Total Partner Pulsa</span>
      <div class="count yellow">{{ $partnerPulsa }}</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-anchor"></i> Total Partner Product</span>
      <div class="count purple">{{ $partnerProduct }}</div>
    </div>
  </div>
  <div class="row tile_count">
  	<div class="col-md-3 col-md-offset-3 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-suitcase"></i> Total Agent Tradisional</span>
      <div class="count green">{{ $agent }}</div>
    </div>
    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-suitcase"></i> Total Agent MLM</span>
      <div class="count green">{{ $agentMlm }}</div>
    </div>
  </div>
  
  <div class="row">
  	<form class="form-inline text-center">
	  	<div class="col-md-12">
			<div class="x_content">
				<select name="f_month" class="form-control select_provider" onchange="this.form.submit()">
					<option value="01" @if($request->f_month == '01') selected @endif>Januari</option>
					<option value="02" @if($request->f_month == '02') selected @endif>Februari</option>
					<option value="03" @if($request->f_month == '03') selected @endif>Maret</option>
					<option value="04" @if($request->f_month == '04') selected @endif>April</option>
					<option value="05" @if($request->f_month == '05') selected @endif>Mei</option>
					<option value="06" @if($request->f_month == '06') selected @endif>Juni</option>
					<option value="07" @if($request->f_month == '07') selected @endif>July</option>
					<option value="08" @if($request->f_month == '08') selected @endif>Agustus</option>
					<option value="09" @if($request->f_month == '09') selected @endif>September</option>
					<option value="10" @if($request->f_month == '10') selected @endif>Oktober</option>
					<option value="11" @if($request->f_month == '11') selected @endif>November</option>
					<option value="12" @if($request->f_month == '12') selected @endif>Desember</option>
				</select>
				<input type="number" value="{{$request->f_year}}" onkeydown="return false" min="2017" step="1" name="f_year" class="form-control" onchange="this.form.submit()">
			</div>
		</div>
	</form>
  </div>
  <div class="row">
  	<div class="col-md-12">
		<div id="container_sales_monthly"></div>
	</div>
  </div>
  <div class="row">
  	<div class="col-md-12">
		<div id="container_deposit"></div>
	</div>
  </div>
  
  <script type="text/javascript">
		Highcharts.chart('container_sales_monthly', {
		    chart: {
		        zoomType: 'xy'
		    },
		    title: {
		        text: 'Sales Monthly'
		    },
		    subtitle: {
		        text: '{{ $thisMonthYearName }}'
		    },
		    xAxis: {
		        max:31,
		        allowDecimals: false
		    },
		    yAxis: {
				min:0,
				labels: {
			        enabled: true,
		            formatter: function(){
		            	return this.value.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1" + ".");
		            },
		            style: {
		                color: Highcharts.getOptions().colors[1]
		            }
		        },
		        title: {
		            text: 'Sales',
		            style: {
		                color: Highcharts.getOptions().colors[1]
		            }
		        },
		        stackLabels: {
		            enabled: true,
		            formatter: function(){
		            	return this.total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1" + ".");
		            },
		            style: {
		                fontWeight: 'bold',
		                color: 'gray'
		            }
		        }
			},
		    tooltip: {
		    	headerFormat: '<b>{point.x}</b><br/>',
		        pointFormatter:function(){
			        return this.series.name+" : "+this.y.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1" + ".");
		        }
		    },
		    plotOptions: {
		        column: {
		            stacking: 'normal'
		        },
		        spline:{
		        	color:'rgb(0, 0, 0)',
		        	marker:{
						radius:3
					}
				}
		    },
		    legend: {
		        layout: 'horizontal',
		        align: 'center',
		        verticalAlign: 'top',
		        y: 40,
		        floating: true,
		        shadow:true,
		        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
		    },
		    series: <?= $dataChartSalesMonthly ?>
		});
		
		Highcharts.chart('container_deposit', {
		    chart: {
		        zoomType: 'xy'
		    },
		    title: {
		        text: 'Deposit Balance'
		    },
		    xAxis: {
		    	categories: ['Agent', 'Partner'],
		    	crosshair: true
		    },
		    yAxis: {
				min:0,
				labels: {
			        enabled: true,
		            
		            style: {
		                color: Highcharts.getOptions().colors[1]
		            }
		        },
		        title: {
		            text: 'Rp',
		            style: {
		                color: Highcharts.getOptions().colors[1]
		            }
		        },
		        stackLabels: {
		            enabled: true,
		            formatter: function(){
		            	return this.total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1" + ".");
		            },
		            style: {
		                fontWeight: 'bold',
		                color: 'gray'
		            }
		        }
			},
		    tooltip: {
		    	headerFormat: '<b>{point.x}</b><br/>',
		    	pointFormatter:function(){
		    		var textHtml = this.series.name+" : "+this.y.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1" + ".");
					if(this.jumlah)textHtml = textHtml+'<br/>Jumlah : '+this.jumlah.toString();
			        return textHtml;
		        }
		    },
		    plotOptions: {
		        column: {
		            stacking: 'normal'
		        }
		    },
		    legend: {
		        layout: 'horizontal',
		        align: 'center',
		        verticalAlign: 'top',
		        y: 40,
		        floating: true,
		        shadow:true,
		        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
		    },
		    series: <?= $dataChartDeposit ?>
		});
		
	</script>
  
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
