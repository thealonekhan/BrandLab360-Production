<tr>
    <td></td>
    <td>{{$eventCategory->totalsForAllResults['ga:totalEvents']}}</td>
    <td>{{$eventCategory->totalsForAllResults['ga:uniqueEvents']}}</td>
    <td>{{$eventCategory->totalsForAllResults['ga:eventValue']}}</td>
    <td>{{round($eventCategory->totalsForAllResults['ga:avgEventValue'], 2)}}</td>
</tr>
@if(!empty($eventCategory->rows))
@foreach($eventCategory->rows as $category)
<tr>
    <td>{{$category[0]}}</td>
    <td>{{$category[1]}}</td>
    <td>{{$category[2]}}</td>
    <td>{{$category[3]}}</td>
    <td>{{$category[4]}}</td>
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