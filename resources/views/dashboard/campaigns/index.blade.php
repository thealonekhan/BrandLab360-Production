@extends('dashboard.base')

@section('content')

<div class="container-fluid">
	<div class="fade-in">
        <?php 
            define( 'SEARCH_STRING', 'on' );
        ?>
            @if($settingConfig->campaigns->campaign_active == "on")
                @include('dashboard.campaigns.filter', ['filters' => $settingConfig->filters])
                @include('dashboard.campaigns.overview', ['overviewCounts' => $overviewCounts, 'overviewSettings' => $settingConfig->overview])
                <div class="row">
    		        <div class="col-md-12 mb-4" id="campaign-tab-data">
                    @include('dashboard.campaigns.campaign-tabs')
                    </div>
                </div>
            @endif

    	<div class="row">
    		<div class="col-md-12 mb-4" id="event-tab-data">
    		
    		</div>
    	</div>
		<!-- /.row -->

	</div>
</div>

@endsection

@section('javascript')


<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}" />
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
<script src="{{ asset('js/campaign.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
let overviewDataLabel = [
    @foreach ($overviewData as $key => $data) 
    "{{$key}}",
    @endforeach];
let overviewData = [
    @foreach ($overviewData as $key => $data) 
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
            url: "{{ route('dashboard.campaign.ajax') }}",
            type:'POST',
            data: {_token:_token, option:quickDateOption, daterange:dateRanges, gamatric:gaMatric},
            beforeSend: function(){
                $(".loading").show();
            },
            success: function(data) {
                $(".loading").hide();
                var overviewDataLabel = [];
                var overviewData = [];
                $.each(data.overviewData, function( index, value ) { 
                    overviewDataLabel.push(index); 
                    overviewData.push(value); 
                });
                $("#campaign-tab-data").html(data.campaignTabsView);
                var matricProps = [data.matricProperties['name'], data.matricProperties['symbol']];

                buildCharts(overviewDataLabel, overviewData, true, matricProps, data.settingConfig);
                
            }
        });

    }

    // Build Charts for overview
    buildCharts(overviewDataLabel, overviewData, false, matricProps, settings);

    function buildCharts(overviewDataLabel, overviewData, update = false, matricProps, settings) {
        // Backend.customToolTip.overview = 15;
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
        Backend.Chart.init(update, settings);
    }

});

</script>
@endsection
