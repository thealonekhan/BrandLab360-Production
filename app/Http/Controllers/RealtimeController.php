<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;
use Ingoldsby\GoogleAnalyticsRealtimeTile\GoogleAnalyticsRealtimeApi;
use Config;
use App\Models\ProjectManagement;

class RealtimeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $realtime;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function overview(Request $request)
    {
        $projectAnalytics = ProjectManagement::with('project')
        ->where('user_id', auth()->user()->id)
        ->where('enabled', true)
        ->first();

        if (!$projectAnalytics) { // if There no project created
            dd('yes1');
            return view('dashboard.homepage-nodata');
        }
        Config::set('dashboard.tiles.google_analytics_realtime.view_id', $projectAnalytics->project->analytics_view_id);
        try { // check if GA analytics ID is valid
            $this->realtime = GoogleAnalyticsRealtimeApi::getAnalyticsRealtime();
        } catch (\Throwable $th) {
            dd('yes2');
            return view('dashboard.homepage-nodata');
        }
        
        return view('dashboard.realtime.overview', ['realtime' => $this->realtime]);
    }
}

?>