<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;

class ApplicationController extends Controller
{
    public function index()
    {
        if (Auth::check()){
            
            $devicesData = [];
            $gaSessionGaphData = [];
            $trafficData = [];

            // Total Counts for sessions, pageviews, bounceRate, users, avgSessionDuration
            $gaAllCounts = Analytics::performQuery(Period::years(1),
            'ga:sessions,ga:pageviews,ga:sessionDuration,ga:bounceRate,ga:users,ga:avgSessionDuration',[
                'dimensions' => 'ga:month'
            ]);

            // dd($gaAllCounts);

            // Total Distribution of Users devices
            $gaAllDevices = Analytics::performQuery(Period::years(1),'ga:sessions',[
                'dimensions' => 'ga:deviceCategory'
            ]);
            
            // Total Distribution of Traffic sources
            $gaAllTrafficOrganic = Analytics::performQuery(Period::years(1),'ga:sessions',[
                'dimensions' => 'ga:source',
                'segment' => 'gaid::-5'
            ]);
            
            $gaAllTrafficDirect = Analytics::performQuery(Period::years(1),'ga:sessions',[
                'dimensions' => 'ga:source',
                'segment' => 'gaid::-7'
            ]);
            
            $gaAllTrafficReferal = Analytics::performQuery(Period::years(1),'ga:sessions',[
                'dimensions' => 'ga:source',
                'segment' => 'gaid::-8'
            ]);
            $topGaCounts = $gaAllCounts->totalsForAllResults;
            if (!empty($gaAllDevices->rows)) {
                $devicesData = $this->setDeviceRows($gaAllDevices->rows);
            }
            $gaSessionGaphData = $this->setMonthRows($gaAllCounts->rows);
            
            $trafficData = $this->setTrafficRows(
                $gaAllTrafficOrganic->totalsForAllResults, 
                $gaAllTrafficDirect->totalsForAllResults, 
                $gaAllTrafficReferal->totalsForAllResults
            );
           
            return view('dashboard.homepage', compact('topGaCounts', 'devicesData', 'trafficData', 'gaSessionGaphData'));
        } else {
            return redirect(route('login'));
        }
        
    }

    public function setDeviceRows($data)
    {
        $results = [];
        foreach ($data as $key => $value) { 
            $results[$value[0]] = $value[1];
        }
        return $results;
    }
    
    public function setTrafficRows($organicData, $directData, $referralData)
    {
        return [
            'organic' => $organicData['ga:sessions'],
            'direct' => $directData['ga:sessions'],
            'referral' => $referralData['ga:sessions']
        ];
    }

    public function setMonthRows($data)
    {
        $monthDataSession = [];
        foreach ($data as $key => $value) {
            $monthDataSession['sessions'][]= $value[1];
            $monthDataSession['durations'][] = gmdate("i", $value[3]);
        }
        return $monthDataSession;
    }

    public function devices()
    {
        if (Auth::check()){
            $devicesGraphData = [];
            $devicesTableData = [];
            // Total Counts for sessions, pageviews, bounceRate, users, avgSessionDuration
            $gaAllDevices = Analytics::performQuery(Period::days(7),
            'ga:sessions,ga:pageviews,ga:bounceRate,ga:users,ga:newUsers,ga:avgSessionDuration,ga:visits,ga:percentNewVisits,ga:percentNewSessions,ga:pageviewsPerSession', [
                'dimensions' => 'ga:deviceCategory,ga:date'
            ]);
            $totalRowsfDevices = $gaAllDevices->rows;
            if (!empty($totalRowsfDevices)) {
                $devicesData = $this->setDeviceRowsNew($totalRowsfDevices);
                $devicesGraphData = $devicesData['graphResults'];
                $devicesTableData = $devicesData['tableResults'];
            }
            $trafficDate = $gaAllDevices->query->startDate. ' To '.$gaAllDevices->query->endDate;
            $gaAllDevicesCounts = $gaAllDevices->totalsForAllResults;
            return view('dashboard.devices', compact('gaAllDevicesCounts', 'devicesGraphData', 'devicesTableData', 'trafficDate'));
        } else {
            return redirect(route('login'));
        }
    }

    public function setDeviceRowsNew($data)
    {
        $graphResults = [];
        $tableResults = [
            'desktop' => [
                'users' => 0,
                'new-users' => 0,
                'sessions' => 0,
                'bounce-rate' => 0,
                'page-session' => 0,
                'avg-session-duration' => 0.0,
                'count' => 0,
            ],
            'mobile' => [
                'users' => 0,
                'new-users' => 0,
                'sessions' => 0,
                'bounce-rate' => 0,
                'page-session' => 0,
                'avg-session-duration' => 0.0,
                'count' => 0,
            ],
            'tablet' => [
                'users' => 0,
                'new-users' => 0,
                'sessions' => 0,
                'bounce-rate' => 0,
                'page-session' => 0,
                'avg-session-duration' => 0.0,
                'count' => 0,
            ],
        ];
        foreach ($data as $key => $value) { 
            $gaDate = Carbon::createFromFormat('Ymd', $value[1])->format('M d');
            if (\Arr::exists($graphResults, $gaDate)) {
                $graphResults[$gaDate] = $graphResults[$gaDate] + (int)$value[6];
            } else {
                $graphResults[$gaDate] = (int)$value[6];
            }
        }

        foreach ($data as $key => $value) {
            if ($value[0] == 'desktop') {
                $tableResults['desktop']['users'] += (int)$value[5];
                $tableResults['desktop']['new-users'] += (int)$value[6];
                $tableResults['desktop']['sessions'] += (int)$value[2];
                $tableResults['desktop']['bounce-rate'] += (float)$value[4];
                $tableResults['desktop']['page-session'] += (float)$value[11];
                $tableResults['desktop']['avg-session-duration'] += (int)$value[7];
                $tableResults['desktop']['count'] += 1;

            }
            if ($value[0] == 'mobile') {
                $tableResults['mobile']['users'] += (int)$value[5];
                $tableResults['mobile']['new-users'] += (int)$value[6];
                $tableResults['mobile']['sessions'] += (int)$value[2];
                $tableResults['mobile']['bounce-rate'] += (float)$value[4];
                $tableResults['mobile']['page-session'] += (float)$value[11];
                $tableResults['mobile']['avg-session-duration'] += (float)$value[7];
                $tableResults['mobile']['count'] += 1;
            }
            if ($value[0] == 'tablet') {
                $tableResults['tablet']['users'] += (int)$value[5];
                $tableResults['tablet']['new-users'] += (int)$value[6];
                $tableResults['tablet']['sessions'] += (int)$value[2];
                $tableResults['tablet']['bounce-rate'] += (float)$value[4];
                $tableResults['tablet']['page-session'] += (float)$value[11];
                $tableResults['tablet']['avg-session-duration'] += (float)$value[7];;
                $tableResults['tablet']['count'] += 1;
            }
        }

        return [
            'graphResults' => $graphResults,
            'tableResults' => $tableResults
        ];

    }

}
