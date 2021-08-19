<tr>
    <td></td>
    <td>{{$eventAction->totalsForAllResults['ga:totalEvents']}}</td>
    <td>{{$eventAction->totalsForAllResults['ga:uniqueEvents']}}</td>
    <td>{{$eventAction->totalsForAllResults['ga:eventValue']}}</td>
    <td>{{round($eventAction->totalsForAllResults['ga:avgEventValue'], 2)}}</td>
</tr>
@if(!empty($eventAction->rows))
@foreach($eventAction->rows as $action)
<tr>
    <td>{{$action[0]}}</td>
    <td>{{$action[1]}}</td>
    <td>{{$action[2]}}</td>
    <td>{{$action[3]}}</td>
    <td>{{$action[4]}}</td>
</tr>
@endforeach
@else
<tr>
    <td>-</td>
    <td>0</td>
    <td>0</td>
    <td>0</td>
    <td>0.0</td>
</tr>
@endif