@if($settings->eventTabs != "null")
<div class="nav-tabs-boxed">
    <ul class="nav nav-tabs" role="tablist">
        @foreach(json_decode($settings->eventTabs) as $key => $tabs)
            <li class="nav-item"><a class="nav-link {{ $key == 0 ? 'active' : '' }}" data-toggle="tab" href="#{{$tabs}}" role="tab" aria-controls="{{$tabs}}" aria-selected="true">{{$tabs}}</a></li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach(json_decode($settings->eventTabs) as $key => $tabs)
        <div class="tab-pane {{ $key == 0 ? 'active' : '' }}" id="{{$tabs}}" role="tabpanel">

            <table class="table table-responsive-sm table-bordered">
                <thead>
                <tr>
                    <th>Event Label</th>
                    <th>Total Events</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($eventData->rows))
                @foreach($eventData->rows as $location)
                @if(!empty($location[0]) && $location[0] == $tabs)
                <tr>
                    <td>{{$location[1]}}</td>
                    <td>{{$location[2]}}</td>
                </tr>
                @endif
                @endforeach
                @endif
                </tbody>
            </table>
            
        </div>
        @endforeach
    </div>
</div>
@endif