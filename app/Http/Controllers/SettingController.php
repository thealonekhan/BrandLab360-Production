<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Analytics;
use Spatie\Analytics\Period;
use AnalyticsHelper;
use App\Models\ProjectManagement;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projectAnalytics = ProjectManagement::with('project')
        ->where('user_id', auth()->user()->id)
        ->where('enabled', true)
        ->first();

        if (!$projectAnalytics) { // if There no project created
            return view('dashboard.homepage-nodata');
        }
        $analytics = $this->helper->getView($projectAnalytics->project->analytics_view_id);

        try { // check if GA analytics ID is valid
            $eventData = $analytics->performQuery(Period::days(365),
            'ga:totalEvents', [
                'dimensions' => 'ga:eventCategory,ga:eventLabel'
            ]);
        } catch (\Throwable $th) {
            return view('dashboard.homepage-nodata');
        }

        $eventTabs = $this->setEventRows($eventData);
        $settings = Setting::where('user_id', auth()->user()->id)->first();
        return view('dashboard.settings.index', compact('settings', 'eventTabs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $config = [
            "filters" => [
                "active" => !empty($request->filters) ? $request->filters : "off",
                "matrix" => !empty($request->matrix) ? $request->matrix : "off",
                "quickDate" => !empty($request->quickDate) ? $request->quickDate : "off",
                "datepicker" => !empty($request->datepicker) ? $request->datepicker : "off",
            ],
            "topCards" => [
                "active" => !empty($request->topCards) ? $request->topCards : "off",
                "sessions" => !empty($request->sessions) ? $request->sessions : "off",
                "users" => !empty($request->users) ? $request->users : "off",
                "visits" => !empty($request->visits) ? $request->visits : "off",
                "bounceRate" => !empty($request->bounceRate) ? $request->bounceRate : "off",
                "avgSessionTime" => !empty($request->avgSessionTime) ? $request->avgSessionTime : "off",
                "sessionsPerUser" => !empty($request->sessionsPerUser) ? $request->sessionsPerUser : "off",
            ],
            "overview" => [
                "active" => !empty($request->overview) ? $request->overview : "off",
                "graph" => !empty($request->graph) ? $request->graph : "off",
                "cards" => [
                    "active" => !empty($request->overviewCards) ? $request->overviewCards : "off",
                    "users" => !empty($request->overviewCardUsers) ? $request->overviewCardUsers : "off",
                    "newUsers" => !empty($request->overviewCardNewUsers) ? $request->overviewCardNewUsers : "off",
                    "sessions" => !empty($request->overviewCardSessions) ? $request->overviewCardSessions : "off",
                    "avgSessionDuration" => !empty($request->overviewCardAvgSessionDuration) ? $request->overviewCardAvgSessionDuration : "off",
                    "bounceRate" => !empty($request->overviewCardBounceRate) ? $request->overviewCardBounceRate : "off",
                    "sessionsPerUser" => !empty($request->overviewCardSessionsPerUser) ? $request->overviewCardSessionsPerUser : "off",
                ],
                "pieGraph" => !empty($request->pieGraph) ? $request->pieGraph : "off",

            ],
            "graphs" => [
                "devices" => !empty($request->devices) ? $request->devices : "off",
                "traffic" => !empty($request->traffic) ? $request->traffic : "off",
            ],
            "realtime" => [
                "liveUserWidget" => !empty($request->liveUserWidget) ? $request->liveUserWidget : "off",
            ],
            "events" => [
                "active" => !empty($request->events) ? $request->events : "off",
                "eventTabs" => $request->eventTabs
            ]
        ];
        
        $settings = Setting::find($id);
        $settings->config = json_encode($config);
        $settings->save();

        $request->session()->flash('message', 'Successfully updated settings');
        return redirect()->route('settings.index');
    }

    public function setEventRows($data)
    {
        $tabs = [];
        if (!empty($data->rows)) {
            foreach ($data->rows as $key => $value) {
                $tabs[] = $value[0];
            }
        }

        return array_unique($tabs);
    }

}
