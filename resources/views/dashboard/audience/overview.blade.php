@extends('dashboard.base')

@section('content')
<div class="container-fluid">
    <div class="fade-in">

    <div class="row">
        <div class="col-sm-4 d-md-block">
            <select class="form-control" id="gamatric">
                <option value="5">User</option>
                <option value="1">Session</option>
                <option value="2">Bounce Rate</option>
                <option value="3">New user</option>
                <option value="4">Avg. Session Duration</option>
            </select>
        </div>
        <div class="col-sm-8 d-md-block">
            <div class="btn-group btn-group-toggle float-right mb-3" data-toggle="buttons">
                <input type="text" name="daterange" class="form-control" />
            </div>
        </div>
    </div>
        
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">Overview</h4>
                    <div class="small text-muted"></div>
                </div>
                <!-- /.col-->
                <div class="col-sm-7 d-md-block">
                    @csrf
                    <button class="btn btn-primary float-right" type="button">
                    <svg class="c-icon">
                    <use xlink:href="{{url('/assets/icons/coreui/free-symbol-defs.svg#cui-cloud-download')}}"></use>
                    </svg>
                    </button>
                    <div class="btn-group btn-group-toggle float-right mr-3" data-toggle="buttons">
                        <label class="btn btn-outline-secondary active">
                            <input id="option1" type="radio" name="options" autocomplete="off" value="ga:date" checked> Day
                        </label>
                        <label class="btn btn-outline-secondary">
                            <input id="option2" type="radio" name="options" autocomplete="off" value="ga:yearMonth"> Month
                        </label>
                        <label class="btn btn-outline-secondary">
                            <input id="option3" type="radio" name="options" autocomplete="off" value="ga:year"> Year
                        </label>
                    </div>
                </div>
                <!-- /.col-->
            </div>
            <!-- /.row-->
            <div id="graph-spinner"></div>
            <div class="c-chart-wrapper" style="height:300px;margin-top:40px;">
            <canvas class="chart" id="overview-chart" height="300"></canvas>
            </div>
        </div>
        <div class="card-footer">
            <div class="row text-center">
                <div class="col-md-6 col-sm-6">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-md mb-sm-5 mb-0">
                            <div class="text-muted">New Users</div><strong id="newUserCount">{{$overviewCounts['ga:newUsers']}}</strong>
                            <div class="progress progress-xs mt-2">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-md mb-sm-5 mb-0">
                            <div class="text-muted">Sessions</div><strong id="sessionCount">{{$overviewCounts['ga:sessions']}}</strong>
                            <div class="progress progress-xs mt-2">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-md mb-sm-5 mb-0">
                            <div class="text-muted">Avg. Session Duration</div><strong id="sessionDurationCount">{{gmdate("H:i:s", $overviewCounts['ga:avgSessionDuration'])}}</strong>
                            <div class="progress progress-xs mt-2">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 col-md mb-sm-5 mb-0">
                            <div class="text-muted">Bounce Rate</div><strong id="bounceRateCount">{{round($overviewCounts['ga:bounceRate'],2)}}%</strong>
                            <div class="progress progress-xs mt-2">
                                <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="c-chart-wrapper">
                        <canvas id="visitors-canvas"></canvas>
                    </div>
                </div>			
            </div>
        </div>
        </div>

    </div>

</div>

<style>

    .spinner-border{
        position: absolute;
        left: 50%;
        top: 30%;
        width: 4rem;
        height: 4rem;
    }

</style>

@endsection

@section('javascript')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
<script src="{{ asset('js/main3.js') }}"></script>
<script type="text/javascript">

$(document).ready(function(){

let overviewDataLabel = [
    @foreach ($overviewData as $key => $data) 
    "{{$key}}",
    @endforeach];
// let overviewData = [
//     @foreach ($overviewData as $key => $data) 
//     @if($matricProperties['name'] == 'Avg. Session Duration')
//         {
//           t: convertHMS("{{$data}}"),
//           y: convertHMS("{{$data}}"),
//         },
//     @else
//         "{{$data}}",
//     @endif
//     @endforeach];
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
        $("#graph-spinner").html('');
        $.ajaxSetup({
            // headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
        });
        jQuery.ajax({
            url: "{{ route('audience.overview.ajax') }}",
            type:'POST',
            data: {_token:_token, option:quickDateOption, daterange:dateRanges, gamatric:gaMatric},
            beforeSend: function(){
                $("#graph-spinner").html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            },
            success: function(data) {
                $("#graph-spinner").html('');
                updateCounts(data.overviewCounts);
                var overviewDataLabel = [];
                var overviewData = [];
                var visitorsDataRaw = [];
                $.each(data.overviewData, function( index, value ) { 
                    overviewDataLabel.push(index); 
                    overviewData.push(value); 
                });
                $.each(data.overviewPercentageData, function( index, value ) { 
                    visitorsDataRaw.push(value); 
                });

                var matricProps = [data.matricProperties['name'], data.matricProperties['symbol']];

                buildCharts(overviewDataLabel, overviewData, visitorsDataRaw, true, matricProps);
                
            }
        });

    }

    // Build Charts for overview
    buildCharts(overviewDataLabel, overviewData, visitorsDataRaw, false, matricProps);

    function buildCharts(overviewDataLabel, overviewData, visitorsDataRaw, update = false, matricProps) {
        // Backend.customToolTip.overview = 15;
        Backend.Chart.overviewChart.data.labels = overviewDataLabel;
        Backend.Chart.overviewChart.data.datasets[0].data = overviewData;
        Backend.Chart.overviewChart.data.datasets[0].label = matricProps[0];
        // if (matricProps[0] == 'Avg. Session Duration') {
        //     Backend.Chart.overviewChart.options.scales = {
        //         yAxes: [{
        //             type: 'time',
        //             time: {
        //             displayFormats: {minute: 'mm'}
        //             }
        //         }]
        //     }
        // }
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
        Backend.Chart.init(update);
    }

    function updateCounts(data) {
        
        var bounceRate = data['ga:bounceRate'];
        $('#sessionCount').text(data['ga:sessions']);
        $('#newUserCount').text(data['ga:newUsers']);
        $('#sessionDurationCount').text(convertHMS(data['ga:avgSessionDuration']));
        $('#bounceRateCount').text(parseFloat(bounceRate).toFixed(2)+"%");
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
