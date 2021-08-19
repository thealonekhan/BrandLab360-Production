<tr>
    <td></td>
    <td>{{$eventLabel->totalsForAllResults['ga:totalEvents']}}</td>
    <td>{{$eventLabel->totalsForAllResults['ga:uniqueEvents']}}</td>
    <td>{{$eventLabel->totalsForAllResults['ga:eventValue']}}</td>
    <td>{{round($eventLabel->totalsForAllResults['ga:avgEventValue'], 2)}}</td>
</tr>
@if(!empty($eventLabel->rows))
@foreach($eventLabel->rows as $label)
<tr>
    <td>{{$label[0]}}</td>
    <td>{{$label[1]}}</td>
    <td>{{$label[2]}}</td>
    <td>{{$label[3]}}</td>
    <td>{{$label[4]}}</td>
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