@extends('dashboard.base')

@section('content')

<?php
    $config = json_decode($settings->config);
    // dd($config);
?>

<div class="container-fluid">
	<div class="fade-in">
        <!-- row -->
        <div class="row">
            <!-- col -->
    		<div class="col-md-12 mb-4">

                <form method="POST" action="/settings/{{ $settings->id }}">
                @csrf
                @method('PUT')
                <!-- Tabs -->
                <div class="nav-tabs-boxed">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#general" role="tab" aria-controls="home" aria-selected="true">General</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#events" role="tab" aria-controls="profile" aria-selected="false">Events</a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane active" id="general" role="tabpanel">
                            
                            <div class="card">
                                <div class="card-body">

                                    <ul class="list-group">
                                        <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center active">
                                            <label class="mr-4 mb-0"><strong>Filters:</strong></label>
                                            <label class="c-switch c-switch-label c-switch-success c-switch-sm mb-0">
                                                <input name="filters" class="c-switch-input filter-head" type="checkbox" {{ $config->filters->active == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                            </label>
                                            <span class="badge badge-primary badge-pill float-right filters-pill"><a class="filters-parent" href="#"><i class="c-icon c-icon-lg cil-caret-top"></i></a></span>
                                        </li>
                                        <ul class="list-group filters-child">
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3"><strong>Matrix:</strong></label>
                                                <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                    <input name="matrix" class="c-switch-input c-switch-sm filter-child" type="checkbox" {{ $config->filters->matrix == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </li>
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3"><strong>Quick Date:</strong></label>
                                                <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                    <input name="quickDate" class="c-switch-input c-switch-sm filter-child" type="checkbox" {{ $config->filters->quickDate == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </li>
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3"><strong>Datepicker:</strong></label>
                                                <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                    <input name="datepicker" class="c-switch-input c-switch-sm filter-child" type="checkbox" {{ $config->filters->datepicker == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </li>
                                        </ul>
                                        <!-- /UL -->
                                    </ul>
                                    <!-- /UL -->

                                    <ul class="list-group mt-2">
                                        <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center active">
                                            <label class="mr-4 mb-0"><strong>Top Cards:</strong></label>
                                            <label class="c-switch c-switch-label c-switch-success c-switch-sm mb-0">
                                                <input name="topCards" class="c-switch-input filter-head" type="checkbox" {{ $config->topCards->active == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                            </label>
                                            <span class="badge badge-primary badge-pill float-right filters-pill"><a class="filters-parent" href="#"><i class="c-icon c-icon-lg cil-caret-top"></i></a></span>
                                        </li>
                                        <ul class="list-group filters-child">
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3"><strong>Sessions:</strong></label>
                                                <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                    <input name="sessions" class="c-switch-input c-switch-sm filter-child" type="checkbox" {{ $config->topCards->sessions == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </li>
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3"><strong>Users:</strong></label>
                                                <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                    <input name="users" class="c-switch-input c-switch-sm filter-child" type="checkbox" {{ $config->topCards->users == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </li>
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3"><strong>Visits:</strong></label>
                                                <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                    <input name="visits" class="c-switch-input c-switch-sm filter-child" type="checkbox" {{ $config->topCards->visits == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </li>
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3"><strong>BounceRate:</strong></label>
                                                <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                    <input name="bounceRate" class="c-switch-input c-switch-sm filter-child" type="checkbox" {{ $config->topCards->bounceRate == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </li>
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3"><strong>Avg. Session Time:</strong></label>
                                                <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                    <input name="avgSessionTime" class="c-switch-input c-switch-sm filter-child" type="checkbox" {{ $config->topCards->avgSessionTime == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </li>
                                        </ul>
                                        <!-- /UL -->
                                    </ul>
                                    <!-- /UL -->

                                    <ul class="list-group mt-2">
                                        <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center active">
                                            <label class="mr-4 mb-0"><strong>Overview:</strong></label>
                                            <label class="c-switch c-switch-label c-switch-success c-switch-sm mb-0">
                                                <input name="overview" class="c-switch-input filter-head" type="checkbox" {{ $config->overview->active == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                            </label>
                                            <span class="badge badge-primary badge-pill float-right filters-pill"><a class="filters-parent" href="#"><i class="c-icon c-icon-lg cil-caret-top"></i></a></span>
                                        </li>
                                        <ul class="list-group filters-child">
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3"><strong>Graph:</strong></label>
                                                <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                    <input name="graph" class="c-switch-input c-switch-sm filter-child" type="checkbox" {{ $config->overview->graph == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </li>
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3"><strong>Cards:</strong></label>
                                                <label class="c-switch c-switch-label c-switch-success c-switch-sm mb-0">
                                                    <input name="overviewCards" class="c-switch-input c-switch-sm filter-child overview-card-head" type="checkbox" {{ $config->overview->cards->active == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </li>
                                                <ul class="list-group filters-child">
                                                    <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                        <label class="mr-4 ml-5"><strong>New Users:</strong></label>
                                                        <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                            <input name="overviewCardNewUsers" class="c-switch-input c-switch-sm filter-child overview-card-child" type="checkbox" {{ $config->overview->cards->newUsers == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                        </label>
                                                    </li>
                                                    <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                        <label class="mr-4 ml-5"><strong>Sessions:</strong></label>
                                                        <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                            <input name="overviewCardSessions" class="c-switch-input c-switch-sm filter-child overview-card-child" type="checkbox" {{ $config->overview->cards->sessions == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                        </label>
                                                    </li>
                                                    <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                        <label class="mr-4 ml-5"><strong>Avg. Session Duration:</strong></label>
                                                        <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                            <input name="overviewCardAvgSessionDuration" class="c-switch-input c-switch-sm filter-child overview-card-child" type="checkbox" {{ $config->overview->cards->avgSessionDuration == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                        </label>
                                                    </li>
                                                    <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                        <label class="mr-4 ml-5"><strong>Bounce Rate:</strong></label>
                                                        <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                            <input name="overviewCardBounceRate" class="c-switch-input c-switch-sm filter-child overview-card-child" type="checkbox" {{ $config->overview->cards->bounceRate == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                        </label>
                                                    </li>
                                                </ul>
                                                <!-- /UL -->
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3"><strong>Pie Graph:</strong></label>
                                                <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                    <input name="pieGraph" class="c-switch-input c-switch-sm filter-child" type="checkbox" {{ $config->overview->pieGraph == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </li>
                                        </ul>
                                        <!-- /UL -->
                                    </ul>
                                    <!-- /UL -->

                                    <ul class="list-group mt-2">
                                        <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center active">
                                            <label class="mr-4 mb-0"><strong>General Graphs:</strong></label>
                                            <span class="badge badge-primary badge-pill float-right filters-pill"><a class="filters-parent" href="#"><i class="c-icon c-icon-lg cil-caret-top"></i></a></span>
                                        </li>
                                        <ul class="list-group filters-child">
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3"><strong>Devices:</strong></label>
                                                <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                    <input name="devices" class="c-switch-input c-switch-sm" type="checkbox" {{ $config->graphs->devices == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </li>
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3"><strong>Traffic:</strong></label>
                                                <label class="c-switch c-switch-label c-switch-info c-switch-sm mb-0">
                                                    <input name="traffic" class="c-switch-input c-switch-sm" type="checkbox" {{ $config->graphs->traffic == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </li>
                                        </ul>
                                        <!-- /UL -->
                                    </ul>
                                    <!-- /UL -->
                                

                                </div>
                            </div>
                            <!-- /card -->

                        </div>
                        <!-- /tab-pane -->

                        <div class="tab-pane" id="events" role="tabpanel">
                            
                            <div class="card">
                                <div class="card-body">

                                    <ul class="list-group">
                                        <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center active">
                                            <label class="mr-4 mb-0"><strong>Events:</strong></label>
                                            <label class="c-switch c-switch-label c-switch-success c-switch-sm mb-0">
                                                <input name="events" class="c-switch-input filter-head" type="checkbox" {{ $config->events->active == "on" ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                            </label>
                                            <span class="badge badge-primary badge-pill float-right filters-pill"><a class="filters-parent" href="#"><i class="c-icon c-icon-lg cil-caret-top"></i></a></span>
                                        </li>
                                        <ul class="list-group filters-child">
                                            <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                <label class="mr-4 ml-3 mb-0"><strong>Event Tabs:</strong></label>
                                            </li>
                                            @foreach($eventTabs as $tab)
                                                <?php 
                                                    $checked = '';
                                                    if($config->events->eventTabs) {
                                                        // dd($settings->eventTabs);
                                                        $checked = in_array($tab, $config->events->eventTabs) ? "checked" : "";
                                                    }
                                                ?>
                                                <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
                                                    <div class="form-check checkbox col-md-4 ml-5">
                                                        <input class="form-check-input filter-child" id="{{$tab}}" name="eventTabs[]" type="checkbox" value="{{$tab}}" {{ $checked }}>
                                                        <label class="form-check-label" for="{{$tab}}">{{$tab}}</label>
                                                    </div>
                                                </li>
                                                
                                            @endforeach
                                        </ul>   
                                        <!-- /UL -->
                                    </ul>
                                    <!-- /UL -->
                                    

                                    

                                </div>  
                            </div>
                            <!-- /card -->

                        </div>
                        <!-- /tab-pane -->

                    </div>
                    <!-- /tab-content -->
                </div>
                <!-- /Tabs -->
                <button class="btn btn-success btn-lg mt-2" type="submit">{{ __('Save') }}</button>
                </form>

            </div>
            <!-- /col -->
        </div>
        <!-- /row -->
    </div>
</div>

<style>

    .list-group{
        border-radius: 0;
    }

</style>

@endsection

@section('javascript')

<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){

    $(".filters-parent").on("click", function(e){
        e.preventDefault();
        var _parent = $(this).closest('.list-group');
        _parent.find(".filters-child").toggle("slow");
        _parent.find('.c-icon').toggleClass('cil-caret-top cil-caret-bottom');
    });

    $('.filter-head').on("change", function () {
        $(this).closest('.list-group').find(".filter-child").prop('checked', $(this).prop('checked'));
    });

    // $('.list-group').on('change', '.filter-child',
    // function () {
    //     $(this).closest('.list-group').find(".filter-head").prop('checked', $(this).closest('.list-group').find('.filter-child:not(:checked)').length == 0);
    // });

    $('.overview-card-head').on("change", function () {
        $(this).closest('.list-group').find(".overview-card-child").prop('checked', $(this).prop('checked'));
    });
});
</script>

@endsection