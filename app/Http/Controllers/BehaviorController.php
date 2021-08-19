<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;

class BehaviorController extends Controller
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
        $gaDateLimit = Period::days(7);
        $gaOverviewDimentions = 'ga:date';
        $gaMatricIndex = 1;
        $overviewData = [];
        $overviewPercentageData = [];
        $gaMatricIndexArray = [
            1 => [
                'name' => 'Total Events',
                'symbol' => ''
            ],
            2 => [
                'name' => 'Unique Events',
                'symbol' => ''
            ],
            3 => [
                'name' => 'Event Value',
                'symbol' => ''
            ],
            4 => [
                'name' => 'Avg. Event Value',
                'symbol' => ''
            ],
            5 => [
                'name' => 'Sessions With Event',
                'symbol' => ''
            ],
            6 => [
               'name' => 'Events / Session With Event',
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
        $gaBehaviorOverViewCounts = Analytics::performQuery($gaDateLimit,
        'ga:totalEvents,ga:uniqueEvents,ga:eventValue,ga:avgEventValue,ga:sessionsWithEvent,ga:eventsPerSessionWithEvent', [
            'dimensions' => $gaOverviewDimentions
        ]);
        
        $eventCategory = Analytics::performQuery($gaDateLimit,
        'ga:totalEvents,ga:uniqueEvents,ga:eventValue,ga:avgEventValue', [
            'dimensions' => 'ga:eventCategory'
        ]);
        $eventAction = Analytics::performQuery($gaDateLimit,
        'ga:totalEvents,ga:uniqueEvents,ga:eventValue,ga:avgEventValue', [
            'dimensions' => 'ga:eventAction'
        ]);
        $eventLabel = Analytics::performQuery($gaDateLimit,
        'ga:totalEvents,ga:uniqueEvents,ga:eventValue,ga:avgEventValue', [
            'dimensions' => 'ga:eventLabel'
        ]);
        

        $overviewCounts = $gaBehaviorOverViewCounts->totalsForAllResults;

        if (!empty($gaBehaviorOverViewCounts->rows)) {
            $overviewData = $this->overviewRows($gaBehaviorOverViewCounts->rows, $dateFormatPatrren[$gaOverviewDimentions], $gaMatricIndex);
        }

        $matricProperties = $gaMatricIndexArray[$gaMatricIndex];
        if($request->ajax()){
            $eventCategoryView = view('dashboard.behavior.category-table-data-ajax', compact('eventCategory'))->render();
            $eventActionView = view('dashboard.behavior.action-table-data-ajax', compact('eventAction'))->render();
            $eventLabelView = view('dashboard.behavior.label-table-data-ajax', compact('eventLabel'))->render();
            return response()->json([
                'overviewCounts' => $overviewCounts,
                'overviewData' => $overviewData,
                'matricProperties' => $matricProperties,
                'eventCategory' => $eventCategoryView,
                'eventAction' => $eventActionView,
                'eventLabel' => $eventLabelView
            ]);
        }
        // dd($gaBehaviorOverViewCounts);
        return view('dashboard.behavior.overview', compact('overviewCounts', 'overviewData', 'matricProperties', 'gaMatricIndexArray', 'eventCategory', 'eventAction', 'eventLabel'));
    }

    public function overviewRows($data, $dateFormat, $matricIndex)
    {
        $dateFormatAssign = $dateFormat == "Y" ? "Y" : "M d";
        $graphResults = [];
        foreach ($data as $key => $value) { 
            $gaDate = Carbon::createFromFormat($dateFormat, $value[0])->format($dateFormatAssign);
            if (\Arr::exists($graphResults, $gaDate)) {
                $graphResults[$gaDate] = $graphResults[$gaDate] + (($matricIndex == 4 || $matricIndex == 6) ? (float)$value[$matricIndex]: (int)$value[$matricIndex]);
            } else {
                $graphResults[$gaDate] = ($matricIndex == 4 || $matricIndex == 6) ? (float)$value[$matricIndex] : (int)$value[$matricIndex];
            }
        }

        return $graphResults;
    }

}
