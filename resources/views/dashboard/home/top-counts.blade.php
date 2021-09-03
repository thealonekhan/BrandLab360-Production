<div class="row">
	<div class="col-sm-6 col-md-2">
		<div class="card">
			<div class="card-body">
				<div class="text-muted text-right mb-4">
					<svg class="c-icon c-icon-2xl">
						<use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-people"></use>
					</svg>
				</div>
				<div class="text-value-lg sessionCount">{{$overviewCounts['ga:sessions']}}</div><small class="text-muted font-weight-bold">Total Sessions</small>
				<div class="progress progress-xs mt-3 mb-0">
					<div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.col-->
	<div class="col-sm-6 col-md-2">
		<div class="card">
			<div class="card-body">
				<div class="text-muted text-right mb-4">
					<svg class="c-icon c-icon-2xl">
						<use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
					</svg>
				</div>
				<div class="text-value-lg userCount">{{$overviewCounts['ga:users']}}</div><small class="text-muted font-weight-bold">Total Users</small>
				<div class="progress progress-xs mt-3 mb-0">
					<div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-2">
		<div class="card">
			<div class="card-body">
				<div class="text-muted text-right mb-4">
					<svg class="c-icon c-icon-2xl">
						<use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
					</svg>
				</div>
				<div class="text-value-lg newVistCount">{{$overviewCounts['ga:newVisits']}}</div><small class="text-muted font-weight-bold">New Visits</small>
				<div class="progress progress-xs mt-3 mb-0">
					<div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.col-->
	<!-- <div class="col-sm-6 col-md-2">
		<div class="card">
			<div class="card-body">
				<div class="text-muted text-right mb-4">
					<svg class="c-icon c-icon-2xl">
						<use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-basket"></use>
					</svg>
				</div>
				<div class="text-value-lg">{{$overviewCounts['ga:pageViews']}}</div><small class="text-muted font-weight-bold">Page Views</small>
				<div class="progress progress-xs mt-3 mb-0">
					<div class="progress-bar bg-warning" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
		</div>
	</div> -->
	<!-- /.col-->
	
	<div class="col-sm-6 col-md-2">
		<div class="card">
			<div class="card-body">
				<div class="text-muted text-right mb-4">
					<svg class="c-icon c-icon-2xl">
						<use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-chart-pie"></use>
					</svg>
				</div>
				<div class="text-value-lg bounceRateCount">{{round($overviewCounts['ga:bounceRate'], 2)}}%</div><small class="text-muted font-weight-bold">Bounce Rate</small>
				<div class="progress progress-xs mt-3 mb-0">
					<div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.col-->
	<div class="col-sm-6 col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="text-muted text-right mb-4">
					<svg class="c-icon c-icon-2xl">
						<use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-speedometer"></use>
					</svg>
				</div>
				<div class="text-value-lg sessionDurationCount">{{gmdate("H:i:s", $overviewCounts['ga:avgSessionDuration'])}}</div><small class="text-muted font-weight-bold">Avg. Session Time</small>
				<div class="progress progress-xs mt-3 mb-0">
					<div class="progress-bar bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- /.col-->
	<!-- <div class="col-sm-6 col-md-2">
		<div class="card">
			<div class="card-body">
				<div class="text-muted text-right mb-4">
					<svg class="c-icon c-icon-2xl">
						<use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-video"></use>
					</svg>
				</div>
				<div class="text-value-lg">0</div><small class="text-muted font-weight-bold">Video Views</small>
				<div class="progress progress-xs mt-3 mb-0">
					<div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			</div>
		</div>
	</div> -->
	<!-- /.col-->
</div>