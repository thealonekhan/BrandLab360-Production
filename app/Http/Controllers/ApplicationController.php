<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\ProjectSetting;
use AnalyticsHelper;
use App\Models\ProjectManagement;
use DB;

class ApplicationController extends Controller
{
    private $helper;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AnalyticsHelper $helper)
    {
        $this->middleware('auth');
        $this->helper = $helper;
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
            8 => [
                'name' => 'Number of Session per User',
                'symbol' => ''
            ],
        ];

        $dateFormatPatrren = [
            'ga:date' => 'Ymd',
            'ga:yearMonth' => 'Ym',
            'ga:year' => 'Y'
        ];

        // Setting up GA with selected project
        $projectAnalytics = ProjectManagement::with('project')
        ->where('user_id', auth()->user()->id)
        ->where('enabled', true)
        ->first();
        if (!$projectAnalytics) {
            return view('dashboard.homepage-nodata');
        }
        $analytics = $this->helper->getView($projectAnalytics->project->analytics_view_id);

        // Matrix Filter
        if ($request->get('gamatric')) {
            $gaMatricIndex = $request->get('gamatric');
        }
        
        // Quick Date filter
        if ($request->get('option')) {
            $gaOverviewDimentions = $request->get('option');
        }

        // Daterange filter
        if ($request->get('daterange')) {
            $dateRanges = $request->get('daterange');
            $dateRangeFormated = explode(" - ", $dateRanges);
            $startDate = Carbon::createFromFormat('Y-m-d', $dateRangeFormated[0]);
            $endDate = Carbon::createFromFormat('Y-m-d', $dateRangeFormated[1]);
            $gaDateLimit = Period::create($startDate, $endDate);

        }

        try {
            // Total Counts for sessions, pageviews, bounceRate, users, avgSessionDuration
            $gaAudienceOverViews = $analytics->performQuery($gaDateLimit,
            'ga:sessions,ga:bounceRate,ga:newUsers,ga:avgSessionDuration,ga:users,ga:newVisits,ga:pageViews,ga:sessionsPerUser', [
                'dimensions' => $gaOverviewDimentions
            ]);


            // Pie Chart: GA New Visits and Returning Visitors
            $gaAudienceOverViewsPercentage = $analytics->performQuery($gaDateLimit,
            'ga:percentNewVisits,ga:users', [
                'dimensions' => 'ga:userType'
            ]);

            // GA Event & Custom Events
            $eventData = $analytics->performQuery($gaDateLimit,
            'ga:totalEvents', [
                'dimensions' => 'ga:eventCategory,ga:eventLabel'
            ]);

            // Ga Devices, Traffic
            $gaAllDevices = $analytics->performQuery($gaDateLimit,'ga:sessions',[
                'dimensions' => 'ga:deviceCategory'
            ]);
            // Total Distribution of Traffic sources
            $gaAllTrafficOrganic = $analytics->performQuery($gaDateLimit,'ga:sessions',[
                'dimensions' => 'ga:source',
                'segment' => 'gaid::-5'
            ]);
            
            $gaAllTrafficDirect = $analytics->performQuery($gaDateLimit,'ga:sessions',[
                'dimensions' => 'ga:source',
                'segment' => 'gaid::-7'
            ]);
            
            $gaAllTrafficReferal = $analytics->performQuery($gaDateLimit,'ga:sessions',[
                'dimensions' => 'ga:source',
                'segment' => 'gaid::-8'
            ]);
            
            $gaAllTrafficSocial = $analytics->performQuery($gaDateLimit,'ga:sessions',[
                'dimensions' => 'ga:socialNetwork',
                'segment' => 'gaid::-1'
            ]);
            
        } catch (\Throwable $th) {
            return view('dashboard.homepage-nodata');
        }

        $overviewCounts = $gaAudienceOverViews->totalsForAllResults;

        if (!empty($gaAudienceOverViews->rows)) {
            $overviewData = $this->overviewRows($gaAudienceOverViews->rows, $dateFormatPatrren[$gaOverviewDimentions], $gaMatricIndex);
        }
        // dd($overviewData);
        if (!empty($gaAudienceOverViewsPercentage->rows)) {
            $overviewPercentageData = $this->calculateOverviewPercentage($gaAudienceOverViewsPercentage);
        }

        $matricProperties = $gaMatricIndexArray[$gaMatricIndex];

        if (!empty($gaAllDevices->rows)) {
            $devicesData = $this->setDeviceRows($gaAllDevices->rows);
        }
        $trafficData = $this->setTrafficRows(
            $gaAllTrafficOrganic->totalsForAllResults, 
            $gaAllTrafficDirect->totalsForAllResults, 
            $gaAllTrafficReferal->totalsForAllResults,
            $gaAllTrafficSocial->rows,
        );
        
        $settings = Setting::where('user_id', auth()->user()->id)->where('override', true)->first();
        if (!$settings) {
            $settings = ProjectSetting::where('project_id', $projectAnalytics->project->id)->first();
        }
        $settingConfig = json_decode($settings->config);
        
        if($request->ajax()){
            $eventTabView = view('dashboard.home.event-tabs-data', compact('eventData', 'settingConfig'))->render();
            return response()->json([
                'overviewCounts' => $overviewCounts,
                'overviewData' => $overviewData,
                'overviewPercentageData' => $overviewPercentageData,
                'matricProperties' => $matricProperties,
                'devicesData' => $devicesData, 
                'trafficData' => $trafficData,
                'eventTabView' => $eventTabView,
                'settings' => $settings,
                'settingConfig' => $settingConfig
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
            'settingConfig'
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
    
    public function setTrafficRows($organicData, $directData, $referralData, $socialData)
    {
        $socialCount = 0;
        if (!empty($socialData)) {
            foreach ($socialData as $key => $value) {
                if ($value[0] != "(not set)") {
                    $socialCount = $socialCount + $value[1];
                }
            }
        }
        
        return [
            'organic' => $organicData['ga:sessions'],
            'direct' => $directData['ga:sessions'],
            'referral' => $referralData['ga:sessions'],
            'social' => (String) $socialCount
        ];
    }

    public function overviewRows($data, $dateFormat, $matricIndex)
    {
        $dateFormatAssign = $dateFormat == "Y" ? "Y" : "M d";
        $graphResults = [];
        foreach ($data as $key => $value) { 
            $gaDate = Carbon::createFromFormat($dateFormat, $value[0])->format($dateFormatAssign);
            if (\Arr::exists($graphResults, $gaDate)) {
                $graphResults[$gaDate] = $graphResults[$gaDate] + ($matricIndex == 4 || $matricIndex == 8  ? round((float)$value[$matricIndex], 2): (int)$value[$matricIndex]);
            } else {
                $graphResults[$gaDate] = $matricIndex == 4 || $matricIndex == 8 ? round((float)$value[$matricIndex], 2) : (int)$value[$matricIndex];
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

    public function selectProject(Request $request)
    {
        if($request->ajax()) {

            DB::table('project_management')
                ->where('user_id', auth()->user()->id)
                ->update(['enabled' => false]);

            DB::table('project_management')
                ->where('user_id', auth()->user()->id)
                ->where('project_id', $request->project)
                ->update(['enabled' => true]);

            return response()->json([], 200);
        }
    }

}
