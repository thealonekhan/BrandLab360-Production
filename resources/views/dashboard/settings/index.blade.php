@extends('dashboard.base')

@section('content')

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

                                    <div class="form-group row">
                                        <label class="mr-4"><strong>Show Filters:</strong></label>
                                        <label class="c-switch c-switch-label c-switch-success">
                                            <input name="showFilters" class="c-switch-input" type="checkbox" {{ $settings->showFilters ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>

                                    <div class="form-group row">
                                        <label class="mr-4"><strong>Show Top Cards:</strong></label>
                                        <label class="c-switch c-switch-label c-switch-success">
                                            <input name="showTopCards" class="c-switch-input" type="checkbox" {{ $settings->showTopCards ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="mr-4"><strong>Show Overview:</strong></label>
                                        <label class="c-switch c-switch-label c-switch-success">
                                            <input name="showOverview" class="c-switch-input" type="checkbox" {{ $settings->showOverview ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="mr-4"><strong>Show Traffic Chart:</strong></label>
                                        <label class="c-switch c-switch-label c-switch-success">
                                            <input name="showTrafficChart" class="c-switch-input" type="checkbox" {{ $settings->showTrafficChart ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="mr-4"><strong>Show Device Chart:</strong></label>
                                        <label class="c-switch c-switch-label c-switch-success">
                                            <input name="showDeviceChart" class="c-switch-input" type="checkbox" {{ $settings->showDeviceChart ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>

                                </div>
                            </div>
                            <!-- /card -->

                        </div>
                        <!-- /tab-pane -->

                        <div class="tab-pane" id="events" role="tabpanel">
                            
                            <div class="card">
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="mr-4"><strong>Show Events:</strong></label>
                                        <label class="c-switch c-switch-label c-switch-success">
                                            <input name="showEvents" class="c-switch-input" type="checkbox" {{ $settings->showEvents ? "checked" : "" }}><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                        </label>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label pl-0"><strong>Events Tabs:</strong></label>
                                        <div class="col-md-9">
                                            <div class="row">
                                            @foreach($eventsTabs as $tab)
                                                <div class="form-check checkbox col-md-4">
                                                    <input class="form-check-input" id="{{$tab}}" name="eventTabs[]" type="checkbox" value="{{$tab}}" {{ in_array($tab, json_decode($settings->eventTabs)) ? "checked" : "" }}>
                                                    <label class="form-check-label" for="{{$tab}}">{{$tab}}</label>
                                                </div>
                                            @endforeach
                                            </div>
                                        </div>
                                    </div>

                                </div>  
                            </div>
                            <!-- /card -->

                        </div>
                        <!-- /tab-pane -->

                    </div>
                    <!-- /tab-content -->
                </div>
                <!-- /Tabs -->
                <button class="btn btn-block btn-success mt-2" type="submit">{{ __('Save') }}</button>
                </form>

            </div>
            <!-- /col -->
        </div>
        <!-- /row -->
    </div>
</div>

@endsection

@section('javascript')

@endsection