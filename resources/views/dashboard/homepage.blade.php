@extends('dashboard.base')

@section('content')

<div class="container-fluid">
	<div class="fade-in">
		
		<!--  && !auth()->user()->hasRole('admin') -->
		@if($settingConfig->filters->active == "on")
			@include('dashboard.home.filter', ['filters' => $settingConfig->filters])
		@endif

		@if($settingConfig->topCards->active == "on")
			@include('dashboard.home.top-counts', ['topCards' => $settingConfig->topCards])
		@endif

		@if($settingConfig->overview->active == "on")
			@include('dashboard.home.overview', ['overviewCounts' => $overviewCounts, 'overviewSettings' => $settingConfig->overview])
		@endif

		@if($settingConfig->events->active == "on")
    	<div class="row">
    		<div class="col-md-12 mb-4" id="event-tab-data">
    		@include('dashboard.home.event-tabs-data', ['eventData' => $eventData, 'settingConfig' => $settingConfig])
    		</div>
    	</div>
		@endif
		<!-- /.row -->

		<div class="row">
			@if($settingConfig->graphs->devices == "on")
			<div class="col-sm-12 col-md-6">
				<div class="card">
					<div class="card-header">Devices
						<div class="card-header-actions"></div>
					</div>
					<div class="card-body">
						<div class="c-chart-wrapper">
							<canvas id="devices-canvas"></canvas>
						</div>
					</div>
				</div>
			</div>
			@endif
			@if($settingConfig->graphs->traffic == "on")
			<div class="col-sm-12 col-md-6">
				<div class="card">
					<div class="card-header">Traffic
						<div class="card-header-actions"></div>
					</div>
					<div class="card-body">
						<div class="c-chart-wrapper">
							<canvas id="traffic-canvas"></canvas>
						</div>
					</div>
				</div>
			</div>
			@endif
		</div>
		<!-- /.row-->

	</div>
</div>

<style>

    /* .spinner-border{
        position: absolute;
        left: 50%;
        top: 30%;
        width: 4rem;
        height: 4rem;
    } */

	

</style>

@endsection

@section('javascript')


<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}" />
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
let devicesDataRaw = [
	@foreach ($devicesData as $key => $data) 
		"{{$data}}",
	@endforeach];
let trafficDataRaw = [
	@foreach ($trafficData as $key => $data) 
		"{{$data}}",
	@endforeach];
let overviewDataLabel = [
    @foreach ($overviewData as $key => $data) 
    "{{$key}}",
    @endforeach];
let overviewData = [
    @foreach ($overviewData as $key => $data) 
        "{{$data}}",
    @endforeach];
let visitorsDataRaw = [
    @foreach ($overviewPercentageData as $key => $data)
        "{{$data}}",
    @endforeach];
let matricProps = [
    "{{$matricProperties['name']}}", 
    "{{$matricProperties['symbol']}}"];
let settings = {!! str_replace('&quot;', '', json_encode((array)$settingConfig)) !!};

	$('input[name="daterange"]').daterangepicker({
	    timePicker: false,
	    startDate: moment().subtract(7, 'days').startOf('day'),
	    endDate: moment().startOf('day'),
	    locale: {
	    format: 'YYYY-MM-DD'
	    }
	});

	$("#gamatric").on("change", function(e){
	    e.preventDefault();
	    var gaMatric =  $(this).val();
	    var dateRanges = $('input[name="daterange"]').val();
	    var quickDateOption = $("input:radio[name='options']:checked").val();
	    var _token = $("input[name='_token']").val();
	    sendRequest(_token, dateRanges, quickDateOption, gaMatric);
	});

	$('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
	    ev.preventDefault();
	    var dateRanges = $(this).val();
	    var quickDateOption = $("input:radio[name='options']:checked").val();
	    var gaMatric = $("#gamatric").val();
	    var _token = $("input[name='_token']").val();
	    sendRequest(_token, dateRanges, quickDateOption, gaMatric);
	});

	$('input:radio[name="options"]').change(function(e){
	    e.preventDefault();
	    var _token = $("input[name='_token']").val();
	    var dateRanges = $('input[name="daterange"]').val();
	    var gaMatric = $("#gamatric").val();
	    var quickDateOption = $(this).val();
	    sendRequest(_token, dateRanges, quickDateOption, gaMatric);
	});

	function sendRequest(_token, dateRanges, quickDateOption, gaMatric) {
        $(".loading").hide();
        $.ajaxSetup({
            // headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        jQuery.ajax({
            url: "{{ route('dashboard.overview.ajax') }}",
            type:'POST',
            data: {_token:_token, option:quickDateOption, daterange:dateRanges, gamatric:gaMatric},
            beforeSend: function(){
                $(".loading").show();
            },
            success: function(data) {
                $(".loading").hide();
                updateCounts(data.overviewCounts);
                var overviewDataLabel = [];
                var overviewData = [];
                var visitorsDataRaw = [];
                var devicesDataRaw = [];
                var trafficDataRaw = [];
                $.each(data.overviewData, function( index, value ) { 
                    overviewDataLabel.push(index); 
                    overviewData.push(value); 
                });
                $.each(data.overviewPercentageData, function( index, value ) { 
                    visitorsDataRaw.push(value); 
                });
                $.each(data.devicesData, function( index, value ) { 
                    devicesDataRaw.push(value); 
                });
                $.each(data.trafficData, function( index, value ) { 
                    trafficDataRaw.push(value); 
                });
                $("#event-tab-data").html(data.eventTabView);

                var matricProps = [data.matricProperties['name'], data.matricProperties['symbol']];

                buildCharts(overviewDataLabel, overviewData, visitorsDataRaw, devicesDataRaw, trafficDataRaw, true, matricProps, data.settingConfig);
                
            }
        });

    }

    // Build Charts for overview
    buildCharts(overviewDataLabel, overviewData, visitorsDataRaw, devicesDataRaw, trafficDataRaw, false, matricProps, settings);

    function buildCharts(overviewDataLabel, overviewData, visitorsDataRaw, devicesDataRaw, trafficDataRaw, update = false, matricProps, settings) {
        // Backend.customToolTip.overview = 15;
        Backend.Chart.deviceDoughnutChart.data.datasets[0].data = devicesDataRaw;
		Backend.Chart.trafficDoughnutChart.data.datasets[0].data = trafficDataRaw;
        Backend.Chart.overviewChart.data.labels = overviewDataLabel;
        Backend.Chart.overviewChart.data.datasets[0].data = overviewData;
        Backend.Chart.overviewChart.data.datasets[0].label = matricProps[0];

        if (matricProps[1] == '%') {
            Backend.Chart.overviewChart.options.tooltips.callbacks.label = function(tooltipItem, data) {
                //get the concerned dataset
                var dataset = data.datasets[tooltipItem.datasetIndex];
                //get the current items value
                var currentValue = dataset.data[tooltipItem.index];
        
                return dataset.label + ": " +currentValue + "%";
            }   
        } else {
            Backend.Chart.overviewChart.options.tooltips.callbacks.label = function(tooltipItem, data) {
                //get the concerned dataset
                var dataset = data.datasets[tooltipItem.datasetIndex];
                //get the current items value
                var currentValue = dataset.data[tooltipItem.index];
        
                return dataset.label + ": " +currentValue;
            }
        }
        Backend.Chart.visitorChart.data.datasets[0].data = [visitorsDataRaw[0], visitorsDataRaw[2]];
        Backend.Chart.init(update, settings);
    }

    function updateCounts(data) {
        
        var bounceRate = data['ga:bounceRate'];
        $('.sessionCount').text(data['ga:sessions']);
        $('.newUserCount').text(data['ga:newUsers']);
        $('.newVistCount').text(data['ga:newVisits']);
        $('.UserCount').text(data['ga:users']);
        $('.sessionDurationCount').text(convertHMS(data['ga:avgSessionDuration']));
        $('.bounceRateCount').text(parseFloat(bounceRate).toFixed(2)+"%");
    }

    function convertHMS(value) {
        const sec = parseInt(value, 10); // convert value to number if it's string
        let hours   = Math.floor(sec / 3600); // get hours
        let minutes = Math.floor((sec - (hours * 3600)) / 60); // get minutes
        let seconds = sec - (hours * 3600) - (minutes * 60); //  get seconds
        // add 0 if value < 10; Example: 2 => 02
        if (hours   < 10) {hours   = "0"+hours;}
        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}
        return hours+':'+minutes+':'+seconds; // Return is HH : MM : SS
    }


});

</script>
@endsection
