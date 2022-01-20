<div class="nav-tabs-boxed">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#campaign" role="tab" aria-controls="campaign" aria-selected="true">campaign</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#source" role="tab" aria-controls="source" aria-selected="true">source</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#medium" role="tab" aria-controls="medium" aria-selected="true">medium</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="campaign" role="tabpanel">
            <table class="table table-responsive-sm table-bordered">
                <thead>
                <tr>
                    <th>Campaign</th>
                    <th>Users</th>
                    <th>New Users</th>
                    <th>Sessions</th>
                    <th>Bounce Rate</th>
                    <th>Avg. Session Durartion</th>
                </tr>
                <tr>
                    <th></th>
                    <th>{{$gaAcquisitionCampaign->totalsForAllResults['ga:users']}}</th>
                    <th>{{$gaAcquisitionCampaign->totalsForAllResults['ga:newUsers']}}</th>
                    <th>{{$gaAcquisitionCampaign->totalsForAllResults['ga:sessions']}}</th>
                    <th>{{round($gaAcquisitionCampaign->totalsForAllResults['ga:bounceRate'], 2)}}</th>
                    <th>{{round($gaAcquisitionCampaign->totalsForAllResults['ga:avgSessionDuration'], 2)}}</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($gaAcquisitionCampaign->rows))
                @foreach($gaAcquisitionCampaign->rows as $campaign)
                @if($campaign[0] != '(not set)')
                <tr>
                    <td>{{$campaign[0]}}</td>
                    <td>{{$campaign[5]}}</td>
                    <td>{{$campaign[3]}}</td>
                    <td>{{$campaign[1]}}</td>
                    <td>{{round($campaign[2], 2)}}</td>
                    <td>{{round($campaign[4], 2)}}</td>
                </tr>
                @endif
                @endforeach
                @endif
                </tbody>
            </table>
            
        </div>
        <div class="tab-pane" id="source" role="tabpanel">
            <table class="table table-responsive-sm table-bordered">
                <thead>
                <tr>
                    <th>Source</th>
                    <th>Users</th>
                    <th>New Users</th>
                    <th>Sessions</th>
                    <th>Bounce Rate</th>
                    <th>Avg. Session Durartion</th>
                </tr>
                <tr>
                    <th></th>
                    <th>{{$gaAcquisitionSource->totalsForAllResults['ga:users']}}</th>
                    <th>{{$gaAcquisitionSource->totalsForAllResults['ga:newUsers']}}</th>
                    <th>{{$gaAcquisitionSource->totalsForAllResults['ga:sessions']}}</th>
                    <th>{{round($gaAcquisitionSource->totalsForAllResults['ga:bounceRate'], 2)}}</th>
                    <th>{{round($gaAcquisitionSource->totalsForAllResults['ga:avgSessionDuration'], 2)}}</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($gaAcquisitionSource->rows))
                @foreach($gaAcquisitionSource->rows as $source)
                @if($source[0] != '(direct)')
                <tr>
                    <td>{{$source[0]}}</td>
                    <td>{{$source[5]}}</td>
                    <td>{{$source[3]}}</td>
                    <td>{{$source[1]}}</td>
                    <td>{{round($source[2], 2)}}</td>
                    <td>{{round($source[4], 2)}}</td>
                </tr>
                @endif
                @endforeach
                @endif
                </tbody>
            </table>
            
        </div>
        <div class="tab-pane" id="medium" role="tabpanel">
            <table class="table table-responsive-sm table-bordered">
                <thead>
                <tr>
                    <th>Medium</th>
                    <th>Users</th>
                    <th>New Users</th>
                    <th>Sessions</th>
                    <th>Bounce Rate</th>
                    <th>Avg. Session Durartion</th>
                </tr>
                <tr>
                    <th></th>
                    <th>{{$gaAcquisitionMedium->totalsForAllResults['ga:users']}}</th>
                    <th>{{$gaAcquisitionMedium->totalsForAllResults['ga:newUsers']}}</th>
                    <th>{{$gaAcquisitionMedium->totalsForAllResults['ga:sessions']}}</th>
                    <th>{{round($gaAcquisitionMedium->totalsForAllResults['ga:bounceRate'], 2)}}</th>
                    <th>{{round($gaAcquisitionMedium->totalsForAllResults['ga:avgSessionDuration'], 2)}}</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($gaAcquisitionMedium->rows))
                @foreach($gaAcquisitionMedium->rows as $medium)
                @if($medium[0] != '(none)')
                <tr>
                    <td>{{$medium[0]}}</td>
                    <td>{{$medium[5]}}</td>
                    <td>{{$medium[3]}}</td>
                    <td>{{$medium[1]}}</td>
                    <td>{{round($medium[2], 2)}}</td>
                    <td>{{round($medium[4], 2)}}</td>
                </tr>
                @endif
                @endforeach
                @endif
                </tbody>
            </table>
            
        </div>
    </div>
</div>