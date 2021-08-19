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
            <canvas class="chart" id="events-overview-canvas" height="300"></canvas>
            </div>
        </div>
        <div class="card-footer">
            <div class="row text-center">
                <div class="col-md-3 col-sm-12 col-md mb-sm-2 mb-0">
                    <div class="text-muted">Total Events</div><strong id="totalEventCount">{{$overviewCounts['ga:totalEvents']}}</strong>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 col-md mb-sm-2 mb-0">
                    <div class="text-muted">Unique Events</div><strong id="uniqueEventCount">{{$overviewCounts['ga:uniqueEvents']}}</strong>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 col-md mb-sm-2 mb-0">
                    <div class="text-muted">Event Value</div><strong id="eventValueCount">{{$overviewCounts['ga:eventValue']}}</strong>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 col-md mb-sm-2 mb-0">
                    <div class="text-muted">Avg. Value</div><strong id="avgEventValueCount">{{round($overviewCounts['ga:avgEventValue'],2)}}</strong>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 col-md mb-sm-2 mb-0">
                    <div class="text-muted">Sessions With Event</div><strong id="sessionsWithEventCount">{{$overviewCounts['ga:sessionsWithEvent']}}%</strong>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-12 col-md mb-sm-2 mb-0">
                    <div class="text-muted">Events / Session With Event </div><strong id="eventPerSessionCount">{{round($overviewCounts['ga:eventsPerSessionWithEvent'],2)}}</strong>
                    <div class="progress progress-xs mt-2">
                        <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>		
            </div>
        </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-12 mb-4">
            <div class="nav-tabs-boxed">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#category" role="tab" aria-controls="home" aria-selected="true">Category</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#action" role="tab" aria-controls="profile" aria-selected="false">Action</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#label" role="tab" aria-controls="messages" aria-selected="false">Label</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="category" role="tabpanel">

                        <table class="table table-responsive-sm table-bordered">
                            <thead>
                            <tr>
                                <th>Event Category</th>
                                <th>Total Events</th>
                                <th>Unique events</th>
                                <th>Event Value</th>
                                <th>Avg Value</th>
                            </tr>
                            </thead>
                            <tbody id="event-category">
                            <tr>
                                <td></td>
                                <td>{{$eventCategory->totalsForAllResults['ga:totalEvents']}}</td>
                                <td>{{$eventCategory->totalsForAllResults['ga:uniqueEvents']}}</td>
                                <td>{{$eventCategory->totalsForAllResults['ga:eventValue']}}</td>
                                <td>{{round($eventCategory->totalsForAllResults['ga:avgEventValue'], 2)}}</td>
                            </tr>
                            @foreach($eventCategory->rows as $category)
                            <tr>
                                <td>{{$category[0]}}</td>
                                <td>{{$category[1]}}</td>
                                <td>{{$category[2]}}</td>
                                <td>{{$category[3]}}</td>
                                <td>{{$category[4]}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                    <div class="tab-pane" id="action" role="tabpanel">
                        <table class="table table-responsive-sm table-bordered">
                            <thead>
                            <tr>
                                <th>Event Action</th>
                                <th>Total Events</th>
                                <th>Unique events</th>
                                <th>Event Value</th>
                                <th>Avg Value</th>
                            </tr>
                            </thead>
                            <tbody id="event-action">
                            <tr>
                                <td></td>
                                <td>{{$eventAction->totalsForAllResults['ga:totalEvents']}}</td>
                                <td>{{$eventAction->totalsForAllResults['ga:uniqueEvents']}}</td>
                                <td>{{$eventAction->totalsForAllResults['ga:eventValue']}}</td>
                                <td>{{round($eventAction->totalsForAllResults['ga:avgEventValue'], 2)}}</td>
                            </tr>
                            @foreach($eventAction->rows as $action)
                            <tr>
                                <td>{{$action[0]}}</td>
                                <td>{{$action[1]}}</td>
                                <td>{{$action[2]}}</td>
                                <td>{{$action[3]}}</td>
                                <td>{{$action[4]}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="label" role="tabpanel">
                        <table class="table table-responsive-sm table-bordered">
                            <thead>
                            <tr>
                                <th>Event Label</th>
                                <th>Total Events</th>
                                <th>Unique events</th>
                                <th>Event Value</th>
                                <th>Avg Value</th>
                            </tr>
                            </thead>
                            <tbody id="event-label">
                            <tr>
                                <td></td>
                                <td>{{$eventLabel->totalsForAllResults['ga:totalEvents']}}</td>
                                <td>{{$eventLabel->totalsForAllResults['ga:uniqueEvents']}}</td>
                                <td>{{$eventLabel->totalsForAllResults['ga:eventValue']}}</td>
                                <td>{{round($eventLabel->totalsForAllResults['ga:avgEventValue'], 2)}}</td>
                            </tr>
                            @foreach($eventLabel->rows as $label)
                            <tr>
                                <td>{{$label[0]}}</td>
                                <td>{{$label[1]}}</td>
                                <td>{{$label[2]}}</td>
                                <td>{{$label[3]}}</td>
                                <td>{{$label[4]}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
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
<script src="{{ asset('js/main4.js') }}"></script>
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
            url: "{{ route('behavior.overview.ajax') }}",
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
                $("#event-category").html(data.eventCategory);
                $("#event-action").html(data.eventAction);
                $("#event-label").html(data.eventLabel);
                buildCharts(overviewDataLabel, overviewData, true, matricProps);
                
            }
        });

    }

    // Build Charts for overview
    buildCharts(overviewDataLabel, overviewData, false, matricProps);

    function buildCharts(overviewDataLabel, overviewData, update = false, matricProps) {
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
        // Backend.Chart.visitorChart.data.datasets[0].data = [visitorsDataRaw[0], visitorsDataRaw[2]];
        Backend.Chart.init(update);
    }

    function updateCounts(data) {
        
        var avgEventValue = data['ga:avgEventValue'];
        var eventsPerSessionWithEvent = data['ga:eventsPerSessionWithEvent'];
        $('#totalEventCount').text(data['ga:totalEvents']);
        $('#uniqueEventCount').text(data['ga:uniqueEvents']);
        $('#eventValueCount').text(data['ga:eventValue']);
        $('#avgEventValueCount').text(parseFloat(avgEventValue).toFixed(2));
        $('#sessionsWithEventCount').text(data['ga:sessionsWithEvent']);
        $('#eventPerSessionCount').text(parseFloat(eventsPerSessionWithEvent).toFixed(2));
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
