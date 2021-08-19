@extends('dashboard.base')

@section('content')

<div class="container-fluid">
    <div class="fade-in">

    <div class="row">
        <div class="col-sm-4 d-md-block">
            <select class="form-control" id="gamatric">
                @foreach($gaMatricIndexArray as $key => $value)
                    <option value="{{$key}}">{{$value['name']}}</option>
                @endforeach
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
                    <h4 class="card-title mb-0">Devices Traffic</h4>
                    <div class="small text-muted">{{$trafficDate}}</div>
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
            <canvas class="chart" id="device-chart" height="300"></canvas>
            </div>
        </div>
        <div class="card-footer">
            <div class="row text-center">
            <div class="col-sm-12 col-md mb-sm-2 mb-0">
                <div class="text-muted">Visits</div><strong id="visitsCount">{{$gaAllDevicesCounts['ga:visits']}} Users ({{round($gaAllDevicesCounts['ga:percentNewVisits'],2)}}%)</strong>
                <div class="progress progress-xs mt-2">
                <div class="progress-bar bg-success" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="col-sm-12 col-md mb-sm-2 mb-0">
                <div class="text-muted">Sessions</div><strong id="sessionsCount">{{$gaAllDevicesCounts['ga:sessions']}} Users ({{round($gaAllDevicesCounts['ga:percentNewSessions'],2)}}%)</strong>
                <div class="progress progress-xs mt-2">
                <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="col-sm-12 col-md mb-sm-2 mb-0">
                <div class="text-muted">Pageviews</div><strong id="pageviewsCount">{{$gaAllDevicesCounts['ga:pageviews']}}</strong>
                <div class="progress progress-xs mt-2">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="col-sm-12 col-md mb-sm-2 mb-0">
                <div class="text-muted">New Users</div><strong id="newUsersCount">{{$gaAllDevicesCounts['ga:newUsers']}}</strong>
                <div class="progress progress-xs mt-2">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="col-sm-12 col-md mb-sm-2 mb-0">
                <div class="text-muted">Bounce Rate</div><strong id="bounceRateCount">{{round($gaAllDevicesCounts['ga:bounceRate'],2)}}%</strong>
                <div class="progress progress-xs mt-2">
                <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            </div>
        </div>
        </div>

    </div>

    <div class="row">
        <!-- /.col-->
        <div class="col-lg-12">
            <div class="card">
            <div class="card-header"><i class="fa fa-align-justify"></i> Bordered Table</div>
            <div class="card-body">
                <table class="table table-responsive-sm table-bordered">
                <thead>
                    <tr>
                    <th>Device Category</th>
                    <th colspan="3">Acquisition</th>
                    <th colspan="3">Behavior</th>
                    </tr>
                </thead>
                <thead>
                    <tr>
                    <th></th>
                    <th>Users</th>
                    <th>New Users</th>
                    <th>Sessions</th>
                    <th>Bounce Rate</th>
                    <th>Page / Session</th>
                    <th>Avg. Session Duration</th>
                    </tr>
                </thead>
                    <tbody id="devicesTableData">
                        @if(!empty($devicesTableData['desktop']))
                        <tr>
                            <td>{{'Desktop'}}</td>
                            <td>{{$devicesTableData ? $devicesTableData['desktop']['users'] : "-"}}</td>
                            <td>{{$devicesTableData ? $devicesTableData['desktop']['new-users'] : "-"}}</td>
                            <td>{{$devicesTableData ? $devicesTableData['desktop']['sessions'] : "-"}}</td>
                            <td>{{!empty($devicesTableData['desktop']['bounce-rate']) ? round(($devicesTableData['desktop']['bounce-rate'] / $devicesTableData['desktop']['count']), 2) : "-"}}%</td>
                            <td>{{!empty($devicesTableData['desktop']['page-session']) ? round(($devicesTableData['desktop']['page-session'] / $devicesTableData['desktop']['count']), 2) : "-"}}</td>
                            <td>{{$devicesTableData ? gmdate("H:i:s", $devicesTableData['desktop']['avg-session-duration']) : "-"}}</td>
                        </tr>
                        @endif
                        @if(!empty($devicesTableData['mobile']))
                        <tr>
                            <td>{{'Mobile'}}</td>
                            <td>{{$devicesTableData ? $devicesTableData['mobile']['users'] : "-"}}</td>
                            <td>{{$devicesTableData ? $devicesTableData['mobile']['new-users'] : "-"}}</td>
                            <td>{{$devicesTableData ? $devicesTableData['mobile']['sessions'] : "-"}}</td>
                            <td>{{!empty($devicesTableData['mobile']['bounce-rate']) ? round(($devicesTableData['mobile']['bounce-rate'] / $devicesTableData['mobile']['count']), 2) : "-"}}%</td>
                            <td>{{!empty($devicesTableData['mobile']['page-session']) ? round(($devicesTableData['mobile']['page-session'] / $devicesTableData['mobile']['count']), 2) : "-"}}</td>
                            <td>{{$devicesTableData ? gmdate("H:i:s", $devicesTableData['mobile']['avg-session-duration']) : "-"}}</td>
                        </tr>
                        @endif
                        @if(!empty($devicesTableData['tablet']))
                        <tr>
                            <td>{{'Tablet'}}</td>
                            <td>{{$devicesTableData ? $devicesTableData['tablet']['users'] : "-"}}</td>
                            <td>{{$devicesTableData ? $devicesTableData['tablet']['new-users'] : "-"}}</td>
                            <td>{{$devicesTableData ? $devicesTableData['tablet']['sessions'] : "-"}}</td>
                            <td>{{!empty($devicesTableData['tablet']['bounce-rate']) ? round(($devicesTableData['tablet']['bounce-rate'] / $devicesTableData['tablet']['count']), 2) : "-"}}%</td>
                            <td>{{!empty($devicesTableData['tablet']['page-session']) ? round(($devicesTableData['tablet']['page-session'] / $devicesTableData['tablet']['count']), 2) : "-"}}</td>
                            <td>{{$devicesTableData ? gmdate("H:i:s", $devicesTableData['tablet']['avg-session-duration']) : "-"}}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            </div>
        </div>
        <!-- /.col-->
    </div>
    <!-- /.row-->

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
<script src="{{ asset('js/main2.js') }}"></script>
<script type="text/javascript">
let devicesDataLabel = [
	@foreach ($devicesGraphData as $key => $data) 
    "{{$key}}",
	@endforeach];
let devicesData = [
	@foreach ($devicesGraphData as $key => $data) 
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
            url: "{{ route('audience.devices.ajax') }}",
            type:'POST',
            data: {_token:_token, option:quickDateOption, daterange:dateRanges, gamatric:gaMatric},
            beforeSend: function(){
                $("#graph-spinner").html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            },
            success: function(data) {
                $("#graph-spinner").html('');
                updateCounts(data.gaAllDevicesCounts);
                var devicesDataLabel = [];
                var devicesData = [];
                $.each(data.devicesGraphData, function( index, value ) { 
                    devicesDataLabel.push(index); 
                    devicesData.push(value); 
                });

                var matricProps = [data.matricProperties['name'], data.matricProperties['symbol']];
                $("#devicesTableData").html(data.devicesTableData)
                buildCharts(devicesDataLabel, devicesData, true, matricProps);
                
            }
        });

    }

    // Build Charts for overview
    buildCharts(devicesDataLabel, devicesData, false, matricProps);

    function buildCharts(devicesDataLabel, devicesData, update, matricProps) {

        Backend.Chart.deviceChart.data.labels = devicesDataLabel;
        Backend.Chart.deviceChart.data.datasets[0].data = devicesData;
        Backend.Chart.deviceChart.data.datasets[0].label = matricProps[0];

        if (matricProps[1] == '%') {
            Backend.Chart.deviceChart.options.tooltips.callbacks.label = function(tooltipItem, data) {
                //get the concerned dataset
                var dataset = data.datasets[tooltipItem.datasetIndex];
                //get the current items value
                var currentValue = dataset.data[tooltipItem.index];
        
                return dataset.label + ": " +currentValue + "%";
            }   
        } else {
            Backend.Chart.deviceChart.options.tooltips.callbacks.label = function(tooltipItem, data) {
                //get the concerned dataset
                var dataset = data.datasets[tooltipItem.datasetIndex];
                //get the current items value
                var currentValue = dataset.data[tooltipItem.index];
        
                return dataset.label + ": " +currentValue;
            }
        }

        Backend.Chart.init(update);
    }

    function updateCounts(data) {
        // console.log(data);        
        var bounceRate = data['ga:bounceRate'];
        $('#visitsCount').text(data['ga:visits']);
        $('#sessionsCount').text(data['ga:sessions']);
        $('#pageviewsCount').text(data['ga:pageviews']);
        $('#newUsersCount').text(data['ga:newUsers']);
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

</script>
@endsection
