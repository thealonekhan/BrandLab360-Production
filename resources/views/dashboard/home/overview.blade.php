<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">Overview</h4>
                <div class="small text-muted"></div>
            </div>
            <!-- /.col-->
            <!-- /.col-->
        </div>
        <!-- /.row-->
        @if($overviewSettings->graph == "on")
        <div class="c-chart-wrapper" style="height:300px;margin-top:40px;">
            <canvas class="chart" id="overview-chart" height="300"></canvas>
        </div>
        @endif
    </div>
    <div class="card-footer">
        <div class="row text-center">
            @if($overviewSettings->cards->active == "on")
            <div class="col-md-6 col-sm-6">
                <div class="row">
                    @if($overviewSettings->cards->users == "on")
                    <div class="col-md-6 col-sm-12 col-md mb-sm-5 mb-0">
                        <div class="text-muted">Users</div><strong class="userCount">{{$overviewCounts['ga:users']}}</strong>
                        <div class="progress progress-xs mt-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    @endif
                    @if($overviewSettings->cards->sessions == "on")
                    <div class="col-md-6 col-sm-12 col-md mb-sm-5 mb-0">
                        <div class="text-muted">Sessions</div><strong class="sessionCount">{{$overviewCounts['ga:sessions']}}</strong>
                        <div class="progress progress-xs mt-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row">
                    @if($overviewSettings->cards->newUsers == "on")
                    <div class="col-md-6 col-sm-12 col-md mb-sm-5 mb-0">
                        <div class="text-muted">New Users</div><strong class="newUserCount">{{$overviewCounts['ga:newUsers']}}</strong>
                        <div class="progress progress-xs mt-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    @endif
                    @if($overviewSettings->cards->sessionsPerUser == "on")
                    <div class="col-md-6 col-sm-12 col-md mb-sm-5 mb-0">
                        <div class="text-muted">Number of Sessions per User</div><strong class="sessionsPerUser">{{round($overviewCounts['ga:sessionsPerUser'], 2)}}</strong>
                        <div class="progress progress-xs mt-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row">
                    @if($overviewSettings->cards->avgSessionDuration == "on")
                    <div class="col-md-6 col-sm-12 col-md mb-sm-5 mb-0">
                        <div class="text-muted">Avg. Session Duration</div><strong class="sessionDurationCount">{{gmdate("H:i:s", $overviewCounts['ga:avgSessionDuration'])}}</strong>
                        <div class="progress progress-xs mt-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    @endif
                    @if($overviewSettings->cards->bounceRate == "on")
                    <div class="col-md-6 col-sm-12 col-md mb-sm-5 mb-0">
                        <div class="text-muted">Bounce Rate</div><strong class="bounceRateCount">{{round($overviewCounts['ga:bounceRate'],2)}}%</strong>
                        <div class="progress progress-xs mt-2">
                            <div class="progress-bar" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            @if($overviewSettings->pieGraph == "on")
            <div class="col-md-6 col-sm-6">
                <div class="c-chart-wrapper">
                    <canvas id="visitors-canvas"></canvas>
                </div>
            </div>
            @endif		
        </div>
    </div>
</div>
</div>