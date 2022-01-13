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

class CampaignController extends Controller
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
            2 => [
                'name' => 'Session',
                'symbol' => ''
            ],
            3 => [
                'name' => 'Bounce Rate',
                'symbol' => '%'
            ],
            4 => [
                'name' => 'Avg. Session Duration',
                'symbol' => ''
            ],
            5 => [
                'name' => 'User',
                'symbol' => ''
            ],
            6 => [
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
            $gaAcquisitionOverViews = $analytics->performQuery($gaDateLimit,
            'ga:sessions,ga:bounceRate,ga:avgSessionDuration,ga:users,ga:sessionsPerUser', [
                'dimensions' => $gaOverviewDimentions.',ga:campaign'
            ]);
            // fetch campaigns
            $gaAcquisitionCampaign = $analytics->performQuery($gaDateLimit,
            'ga:sessions,ga:bounceRate,ga:newUsers,ga:avgSessionDuration,ga:users', [
                'dimensions' => 'ga:campaign'
            ]);
            // fetch sources
            $gaAcquisitionSource = $analytics->performQuery($gaDateLimit,
            'ga:sessions,ga:bounceRate,ga:newUsers,ga:avgSessionDuration,ga:users', [
                'dimensions' => 'ga:source'
            ]);
            // fetch mediums
            $gaAcquisitionMedium = $analytics->performQuery($gaDateLimit,
            'ga:sessions,ga:bounceRate,ga:newUsers,ga:avgSessionDuration,ga:users', [
                'dimensions' => 'ga:medium'
            ]);
            
        } catch (\Throwable $th) {
            return view('dashboard.homepage-nodata');
        }

        $overviewCounts = $gaAcquisitionOverViews->totalsForAllResults;

        if (!empty($gaAcquisitionOverViews->rows)) {
            $overviewData = $this->overviewRows($gaAcquisitionOverViews->rows, $dateFormatPatrren[$gaOverviewDimentions], $gaMatricIndex);
        }

        $matricProperties = $gaMatricIndexArray[$gaMatricIndex];
        
        $settings = null;
        $general_settings = Setting::where('user_id', auth()->user()->id)->first();
        $project_settings = ProjectSetting::where('project_id', $projectAnalytics->project->id)->first();

        if (!empty($project_settings)) {
            if ($general_settings->override) {
                $settings = $general_settings;
            } else {
                $settings = $project_settings;
            }
        } else {
            $settings = $general_settings;
        }
        
        // dd($settings);
        $settingConfig = json_decode($settings->config);
        
        if($request->ajax()){
            $campaignTabsView = view('dashboard.campaigns.campaign-tabs', compact('gaAcquisitionCampaign', 'gaAcquisitionSource', 'gaAcquisitionMedium'))->render();
            return response()->json([
                'overviewCounts' => $overviewCounts,
                'overviewData' => $overviewData,
                'campaignTabsView' => $campaignTabsView,
                'matricProperties' => $matricProperties,
                'settings' => $settings,
                'settingConfig' => $settingConfig
            ]);
        }


        return view('dashboard.campaigns.index', compact(
            'overviewCounts', 
            'overviewData',
            'gaAcquisitionCampaign',
            'gaAcquisitionSource',
            'gaAcquisitionMedium',
            'matricProperties', 
            'gaMatricIndexArray',
            'settings',
            'settingConfig'
        ));
        
    }

    public function overviewRows($data, $dateFormat, $matricIndex)
    {
        $dateFormatAssign = $dateFormat == "Y" ? "Y" : "M d";
        $graphResults = [];
        foreach ($data as $key => $value) { 
            $gaDate = Carbon::createFromFormat($dateFormat, $value[0])->format($dateFormatAssign);
            if (\Arr::exists($graphResults, $gaDate)) {
                $graphResults[$gaDate] = $graphResults[$gaDate] + ($matricIndex == 4 || $matricIndex == 6  ? round((float)$value[$matricIndex], 2): (int)$value[$matricIndex]);
            } else {
                $graphResults[$gaDate] = $matricIndex == 4 || $matricIndex == 6 ? round((float)$value[$matricIndex], 2) : (int)$value[$matricIndex];
            }
        }
        return $graphResults;
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
