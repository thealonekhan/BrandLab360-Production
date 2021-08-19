<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;

class AudienceController extends Controller
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

    public function overview(Request $request)
    {
        // dd(Analytics::getAnalyticsService());
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
        'ga:sessions,ga:bounceRate,ga:newUsers,ga:avgSessionDuration,ga:users,ga:newVisits', [
            'dimensions' => $gaOverviewDimentions
        ]);

        // dd($gaAudienceOverViews);

        $gaAudienceOverViewsPercentage = Analytics::performQuery($gaDateLimit,
        'ga:percentNewVisits,ga:users', [
            'dimensions' => 'ga:userType'
        ]);

        $overviewCounts = $gaAudienceOverViews->totalsForAllResults;

        if (!empty($gaAudienceOverViews->rows)) {
            $overviewData = $this->overviewRows($gaAudienceOverViews->rows, $dateFormatPatrren[$gaOverviewDimentions], $gaMatricIndex);
        }
        // dd($overviewData);
        if (!empty($gaAudienceOverViewsPercentage->rows)) {
            $overviewPercentageData = $this->calculateOverviewPercentage($gaAudienceOverViewsPercentage);
        }

        $matricProperties = $gaMatricIndexArray[$gaMatricIndex];

        if($request->ajax()){
            return response()->json([
                'overviewCounts' => $overviewCounts,
                'overviewData' => $overviewData,
                'overviewPercentageData' => $overviewPercentageData,
                'matricProperties' => $matricProperties,
            ]);
        }

        // dd($overviewPercentageData);
        return view('dashboard.audience.overview', compact('overviewCounts', 'overviewData', 'overviewPercentageData', 'matricProperties', 'gaMatricIndexArray'));
        
    }

    public function devices(Request $request)
    {
        $devicesGraphData = [];
        $devicesTableData = [];
        $gaDateLimit = Period::days(7);
        $gaOverviewDimentions = 'ga:date';
        $gaMatricIndex = 2;
        $gaMatricIndexArray = [
            2 => [
                'name' => 'Session',
                'symbol' => ''
            ],
            4 => [
                'name' => 'Bounce Rate',
                'symbol' => '%'
            ],
            5 => [
                'name' => 'User',
                'symbol' => ''
            ],
            7 => [
                'name' => 'Avg. Session Duration',
                'symbol' => ''
            ]
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
        $gaAllDevices = Analytics::performQuery($gaDateLimit,
        'ga:sessions,ga:pageviews,ga:bounceRate,ga:users,ga:newUsers,ga:avgSessionDuration,ga:visits,ga:percentNewVisits,ga:percentNewSessions,ga:pageviewsPerSession', [
            'dimensions' => 'ga:deviceCategory,'.$gaOverviewDimentions
        ]);
        // dd($gaAllDevices);
        $totalRowsfDevices = $gaAllDevices->rows;
        if (!empty($totalRowsfDevices)) {
            $devicesData = $this->setDeviceRowsNew($totalRowsfDevices, $dateFormatPatrren[$gaOverviewDimentions], $gaMatricIndex);
            $devicesGraphData = $devicesData['graphResults'];
            $devicesTableData = $devicesData['tableResults'];
        }
        $trafficDate = $gaAllDevices->query->startDate. ' To '.$gaAllDevices->query->endDate;
        $gaAllDevicesCounts = $gaAllDevices->totalsForAllResults;
        $matricProperties = $gaMatricIndexArray[$gaMatricIndex];
        // dd($gaAllDevicesCounts);
        if($request->ajax()){
            $devicesTableDataView = view('dashboard.audience.device-table-data-ajax', compact('devicesTableData'))->render();
            return response()->json([
                'gaAllDevicesCounts' => $gaAllDevicesCounts,
                'devicesGraphData' => $devicesGraphData,
                'devicesTableData' => $devicesTableDataView,
                'trafficDate' => $trafficDate,
                'matricProperties' => $matricProperties,
            ]);
        }

        return view('dashboard.audience.devices', compact('gaAllDevicesCounts', 'devicesGraphData', 'devicesTableData', 'trafficDate', 'matricProperties', 'gaMatricIndexArray'));
    }
    

    public function setDeviceRowsNew($data, $dateFormat, $matricIndex)
    {
        $graphResults = [];
        $dateFormatAssign = $dateFormat == "Y" ? "Y" : "M d";
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
            $gaDate = Carbon::createFromFormat($dateFormat, $value[1])->format($dateFormatAssign);
            if (\Arr::exists($graphResults, $gaDate)) {
                $graphResults[$gaDate] = $graphResults[$gaDate] + (int)$value[$matricIndex];
            } else {
                $graphResults[$gaDate] = (int)$value[$matricIndex];
            }
        }

        foreach ($data as $key => $value) {
            if ($value[0] == 'desktop') {
                $tableResults['desktop']['users'] += (int)$value[5];
                $tableResults['desktop']['new-users'] += (int)$value[6];
                $tableResults['desktop']['sessions'] += (int)$value[2];
                $tableResults['desktop']['bounce-rate'] += (float)$value[4];
                $tableResults['desktop']['page-session'] += (float)$value[11];
                $tableResults['desktop']['avg-session-duration'] += (float)$value[7];
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
