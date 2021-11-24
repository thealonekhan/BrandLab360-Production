<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectSetting;
use App\Models\Project;
use App\Models\Setting;
use Analytics;
use Spatie\Analytics\Period;
use AnalyticsHelper;
use App\Models\ProjectManagement;
use Illuminate\Support\Facades\Auth;

class ProjectSettingController extends Controller
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
    public function show($id)
    {
        $project = Project::find($id);
        $analytics = $this->helper->getView($project->analytics_view_id);

        try { // check if GA analytics ID is valid
            $eventData = $analytics->performQuery(Period::days(365),
            'ga:totalEvents', [
                'dimensions' => 'ga:eventCategory,ga:eventLabel'
            ]);
        } catch (\Throwable $th) {
            return view('dashboard.homepage-nodata');
        }

        $eventTabs = $this->setEventRows($eventData);
        // $settings = ProjectSetting::where('project_id', $id)->first();
        if (!$settings = ProjectSetting::where('project_id', $id)->first()) {
            $settings = Setting::where('user_id', auth()->user()->id)->first();
        }

        return view('dashboard.settings.project-settings', compact('settings', 'eventTabs', 'project'));
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
        
        
        if ($update_settings = ProjectSetting::where('project_id', $id)->first()) { // create
            $update_settings->config = json_encode($config);
            $update_settings->user_id = auth()->user()->id;
            $update_settings->save();
        } else { // update
            $settings = new ProjectSetting();
            $settings->config = json_encode($config);
            $settings->user_id = auth()->user()->id;
            $settings->project_id = $id;
            $settings->save();
        }

        // update settings for all users of this project
        $project_users =  ProjectManagement::where('project_id', $id)->pluck('user_id');
        Setting::whereIn('user_id', $project_users)->update(['override' => false]);

        $request->session()->flash('message', 'Successfully updated project settings');
        return redirect()->route('projects.index');
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
