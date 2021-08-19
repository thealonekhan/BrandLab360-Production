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