<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Analytics;
use Spatie\Analytics\Period;

class SettingController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $eventData = Analytics::performQuery(Period::days(365),
        'ga:totalEvents', [
            'dimensions' => 'ga:eventCategory,ga:eventLabel'
        ]);
        $eventsTabs = $this->setEventRows($eventData);
        $settings = Setting::first();
        return view('dashboard.settings.index', compact('settings', 'eventsTabs'));
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

        $settings = Setting::find($id);
        $settings->showFilters = !empty($request->showFilters) ? 1 : 0;
        $settings->showTopCards = !empty($request->showTopCards) ? 1 : 0;
        $settings->showOverview = !empty($request->showOverview) ? 1 : 0;
        $settings->showEvents = !empty($request->showEvents) ? 1 : 0;
        $settings->showTrafficChart = !empty($request->showTrafficChart) ? 1 : 0;
        $settings->showDeviceChart = !empty($request->showDeviceChart) ? 1 : 0;
        $settings->eventTabs = json_encode($request->eventTabs);
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
