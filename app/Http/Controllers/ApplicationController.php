<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;
use App\Models\Setting;

class ApplicationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $devicesData = [];
        $trafficData = [];
        $matricData = [];
        $gaDateLimit = Period::days(7);
        $gaOverviewDimentions = 'ga:date';
        $gaMatricIndex = 5;
        $overviewData = [];
        $overviewPercentageData = [];
        $gaMatricIndexArray = [
            1 => [
                'name' => 'Session',
                'symbol' => ''
            ],
            2 => [
                'name' => 'Bounce Rate',
                'symbol' => '%'
            ],
            3 => [
                'name' => 'New user',
                'symbol' => ''
            ],
            4 => [
                'name' => 'Avg. Session Duration',
                'symbol' => ''
            ],
            5 => [
                'name' => 'User',
                'symbol' => ''
            ],
        ];

        $dateFormatPatrren = [
            'ga:date' => 'Ymd',
            'ga:yearMonth' => 'Ym',
            'ga:year' => 'Y'
        ];

        if ($request->get('gamatric')) {
            $gaMatricIndex = $request->get('gamatric');
        }
        
        if ($request->get('option')) {
            $gaOverviewDimentions = $request->get('option');
        }

        if ($request->get('daterange')) {
            $dateRanges = $request->get('daterange');
            $dateRangeFormated = explode(" - ", $dateRanges);
            $startDate = Carbon::createFromFormat('Y-m-d', $dateRangeFormated[0]);
            $endDate = Carbon::createFromFormat('Y-m-d', $dateRangeFormated[1]);
            $gaDateLimit = Period::create($startDate, $endDate);

        }

        // Total Counts for sessions, pageviews, bounceRate, users, avgSessionDuration
        $gaAudienceOverViews = Analytics::performQuery($gaDateLimit,
        'ga:sessions,ga:bounceRate,ga:newUsers,ga:avgSessionDuration,ga:users,ga:newVisits,ga:pageViews', [
            'dimensions' => $gaOverviewDimentions
        ]);

        $gaAudienceOverViewsPercentage = Analytics::performQuery($gaDateLimit,
        'ga:percentNewVisits,ga:users', [
            'dimensions' => 'ga:userType'
        ]);

        $eventData = Analytics::performQuery($gaDateLimit,
        'ga:totalEvents', [
            'dimensions' => 'ga:eventCategory,ga:eventLabel'
        ]);
        
        // dd($eventData);
        $overviewCounts = $gaAudienceOverViews->totalsForAllResults;

        if (!empty($gaAudienceOverViews->rows)) {
            $overviewData = $this->overviewRows($gaAudienceOverViews->rows, $dateFormatPatrren[$gaOverviewDimentions], $gaMatricIndex);
        }
        // dd($overviewData);
        if (!empty($gaAudienceOverViewsPercentage->rows)) {
            $overviewPercentageData = $this->calculateOverviewPercentage($gaAudienceOverViewsPercentage);
        }

        $matricProperties = $gaMatricIndexArray[$gaMatricIndex];

        $gaAllDevices = Analytics::performQuery($gaDateLimit,'ga:sessions',[
            'dimensions' => 'ga:deviceCategory'
        ]);
        
        // Total Distribution of Traffic sources
        $gaAllTrafficOrganic = Analytics::performQuery($gaDateLimit,'ga:sessions',[
            'dimensions' => 'ga:source',
            'segment' => 'gaid::-5'
        ]);
        
        $gaAllTrafficDirect = Analytics::performQuery($gaDateLimit,'ga:sessions',[
            'dimensions' => 'ga:source',
            'segment' => 'gaid::-7'
        ]);
        
        $gaAllTrafficReferal = Analytics::performQuery($gaDateLimit,'ga:sessions',[
            'dimensions' => 'ga:source',
            'segment' => 'gaid::-8'
        ]);

        if (!empty($gaAllDevices->rows)) {
            $devicesData = $this->setDeviceRows($gaAllDevices->rows);
        }
        
        $trafficData = $this->setTrafficRows(
            $gaAllTrafficOrganic->totalsForAllResults, 
            $gaAllTrafficDirect->totalsForAllResults, 
            $gaAllTrafficReferal->totalsForAllResults
        );
        // dd($eventData);
        $settings = Setting::first();
        $settingArray = $settings->toArray();

        if($request->ajax()){
            $eventTabView = view('dashboard.home.event-tabs-data', compact('eventData', 'settings'))->render();
            return response()->json([
                'overviewCounts' => $overviewCounts,
                'overviewData' => $overviewData,
                'overviewPercentageData' => $overviewPercentageData,
                'matricProperties' => $matricProperties,
                'devicesData' => $devicesData, 
                'trafficData' => $trafficData,
                'eventTabView' => $eventTabView,
                'settings' => $settings,
                'settingArray' => $settingArray
            ]);
        }


        return view('dashboard.homepage', compact(
            'overviewCounts', 
            'overviewData', 
            'overviewPercentageData', 
            'matricProperties', 
            'devicesData', 
            'trafficData',
            'gaMatricIndexArray',
            'eventData',
            'settings',
            'settingArray'
        ));
        
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

    public function overviewRows($data, $dateFormat, $matricIndex)
    {
        $dateFormatAssign = $dateFormat == "Y" ? "Y" : "M d";
        $graphResults = [];
        foreach ($data as $key => $value) { 
            $gaDate = Carbon::createFromFormat($dateFormat, $value[0])->format($dateFormatAssign);
            if (\Arr::exists($graphResults, $gaDate)) {
                $graphResults[$gaDate] = $graphResults[$gaDate] + ($matricIndex == 4 ? (float)$value[$matricIndex]: (int)$value[$matricIndex]);
            } else {
                $graphResults[$gaDate] = $matricIndex == 4 ? (float)$value[$matricIndex] : (int)$value[$matricIndex];
            }
        }

        return $graphResults;
    }

    public function calculateOverviewPercentage($data)
    {
        $totalUsers = $data->totalsForAllResults['ga:users'];
        $mapArray = [];
        foreach ($data->rows as $key => $value) {
            if ($value[0] == "New Visitor") {
                $mapArray['new-visits'] = $value[2];
            }
            if ($value[0] == "Returning Visitor") {
                $mapArray['returning-vists'] = $value[2];
            }
        }

        $percentageNewVisits = !empty($mapArray['new-visits']) ? round(($mapArray['new-visits'] / $totalUsers) * 100 , 2) : 0;
        $countNewVisits = !empty($mapArray['new-visits']) ? $mapArray['new-visits'] : 0;
        $percentageReturningVisits = !empty($mapArray['returning-vists']) ? round(($mapArray['returning-vists'] / $totalUsers) * 100 , 2) : 0;
        $countReturningVisits = !empty($mapArray['returning-vists']) ? $mapArray['returning-vists'] : 0;

        return [
            'new-visits' => $percentageNewVisits,
            'new-visits-count' => $countNewVisits,
            'returning-vists' => $percentageReturningVisits,
            'returning-vists-count' => $countReturningVisits
        ];
    }

}
