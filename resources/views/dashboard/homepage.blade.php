@extends('dashboard.base')

@section('content')

					<div class="container-fluid">
						<div class="fade-in">
							<div class="row">
								<div class="col-sm-6 col-md-2">
									<div class="card">
										<div class="card-body">
											<div class="text-muted text-right mb-4">
												<svg class="c-icon c-icon-2xl">
													<use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-people"></use>
												</svg>
											</div>
											<div class="text-value-lg">{{$topGaCounts['ga:sessions']}}</div><small class="text-muted font-weight-bold">Total Sessions</small>
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
											<div class="text-value-lg">{{$topGaCounts['ga:users']}}</div><small class="text-muted font-weight-bold">Unique Users</small>
											<div class="progress progress-xs mt-3 mb-0">
												<div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
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
													<use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-basket"></use>
												</svg>
											</div>
											<div class="text-value-lg">{{$topGaCounts['ga:pageviews']}}</div><small class="text-muted font-weight-bold">Page Views</small>
											<div class="progress progress-xs mt-3 mb-0">
												<div class="progress-bar bg-warning" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
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
													<use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-speedometer"></use>
												</svg>
											</div>
											<div class="text-value-lg">{{gmdate("H:i:s", $topGaCounts['ga:avgSessionDuration'])}}</div><small class="text-muted font-weight-bold">Avg. Session Time</small>
											<div class="progress progress-xs mt-3 mb-0">
												<div class="progress-bar bg-danger" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
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
													<use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-chart-pie"></use>
												</svg>
											</div>
											<div class="text-value-lg">{{round($topGaCounts['ga:bounceRate'], 2)}}%</div><small class="text-muted font-weight-bold">Bounce Rate</small>
											<div class="progress progress-xs mt-3 mb-0">
												<div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
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
													<use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-video"></use>
												</svg>
											</div>
											<div class="text-value-lg">0</div><small class="text-muted font-weight-bold">Video Views</small>
											<div class="progress progress-xs mt-3 mb-0">
												<div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
										</div>
									</div>
								</div>
								<!-- /.col-->
							</div>
							<!-- /.row -->
							<div class="row">
								<div class="col-sm-6 col-md-6">
									<div class="card">
										<div class="card-header">Devices
											<div class="card-header-actions"></div>
										</div>
										<div class="card-body">
											<div class="c-chart-wrapper">
												<canvas id="devices-canvas"></canvas>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-6">
									<div class="card">
										<div class="card-header">Traffic
											<div class="card-header-actions"></div>
										</div>
										<div class="card-body">
											<div class="c-chart-wrapper">
												<canvas id="traffic-canvas"></canvas>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- /.row-->
							<div class="row">
								<div class="col-sm-6">
									<div class="card">
										<div class="card-body">
											<div class="c-chart-wrapper" style="height:300px;margin-top:40px;">
												<canvas class="chart" id="session-chart" height="300"></canvas>
											</div>
										</div>
									</div>
									<!-- /.card-->
								</div>
								<!-- /.col-->
								<div class="col-sm-6">
									<div class="card">
										<div class="card-body">
											<div class="c-chart-wrapper" style="height:300px;margin-top:40px;">
												<canvas class="chart" id="session-duration-chart" height="300"></canvas>
											</div>
										</div>
									</div>
									<!-- /.card-->
								</div>
								<!-- /.col-->
							</div>
							<!-- /.row-->
							<div class="row">
								<div class="col-sm-12 col-md-6">
									<div class="card">
										<div class="card-header">Performance of Product</div>
										<div class="card-body">

											<div class="row">
												<div class="col-sm-12 col-md-4">
													<ul class="list-group">
														<li class="list-group-item border-0">
															Product #001<br>
															<small class="text-muted">310 hotspot clicks</small><br>
															<small class="text-muted">208 Interactions</small>
														</li>
														<li class="list-group-item border-0">
															Product #002<br>
															<small class="text-muted">310 hotspot clicks</small><br>
															<small class="text-muted">208 Interactions</small>
														</li>
														<li class="list-group-item border-0">
															Product #003<br>
															<small class="text-muted">310 hotspot clicks</small><br>
															<small class="text-muted">208 Interactions</small>
														</li>
													</ul>
												</div>
												<!-- /.col-->
												<div class="col-sm-12 col-md-4">
													<ul class="list-group">
														<li class="list-group-item border-0">
															Product #004<br>
															<small class="text-muted">310 hotspot clicks</small><br>
															<small class="text-muted">208 Interactions</small>
														</li>
														<li class="list-group-item border-0">
															Product #005<br>
															<small class="text-muted">310 hotspot clicks</small><br>
															<small class="text-muted">208 Interactions</small>
														</li>
														<li class="list-group-item border-0">
															Product #006<br>
															<small class="text-muted">310 hotspot clicks</small><br>
															<small class="text-muted">208 Interactions</small>
														</li>
													</ul>
												</div>
												<!-- /.col-->
												<div class="col-sm-12 col-md-4">
													<ul class="list-group">
														<li class="list-group-item border-0">
															Product #007<br>
															<small class="text-muted">310 hotspot clicks</small><br>
															<small class="text-muted">208 Interactions</small>
														</li>
														<li class="list-group-item border-0">
															Product #008<br>
															<small class="text-muted">310 hotspot clicks</small><br>
															<small class="text-muted">208 Interactions</small>
														</li>
														<li class="list-group-item border-0">
															Product #009<br>
															<small class="text-muted">310 hotspot clicks</small><br>
															<small class="text-muted">208 Interactions</small>
														</li>
													</ul>
												</div>
												<!-- /.col-->
											</div>
											<!-- /.row-->

										</div>
									</div>
									<!-- /.card-->
								</div>
								<!-- /.col-->
								<div class="col-sm-12 col-md-6">
									<div class="card">
										<div class="card-header">Virtual Location Visits
											<div class="card-header-actions"></div>
										</div>
										<div class="card-body">
										<div class="row">
											<div class="col-sm-6 col-md-6">
												<div class="c-chart-wrapper">
													<canvas id="vitual-location-canvas-1"></canvas>
												</div>
											</div>
											<div class="col-sm-6 col-md-6">
												<div class="c-chart-wrapper">
													<canvas id="vitual-location-canvas-2"></canvas>
												</div>
											</div>
										</div>
										<div>
									</div>
									<!-- /.card-->
								</div>
								<!-- /.col-->
							</div>
							<!-- /.row-->
							</div>
							</div>

							<div class="row">
								<div class="col-sm-12 col-md-6">
									<div class="card">
											<div class="card-header">Content Interaction Details
												<div class="card-header-actions"></div>
											</div>
											<div class="card-body">
												<div class="row">
													<div class="col-sm-6 col-md-6">
														<div class="c-chart-wrapper">
															<canvas id="content-interaction-canvas-1"></canvas>
														</div>
													</div>
													<div class="col-sm-6 col-md-6">
														<div class="c-chart-wrapper">
															<canvas id="content-interaction-canvas-2"></canvas>
														</div>
													</div>
												</div>
											</div>
									<!-- /.card-->
									</div>
								</div>
								<!-- /.col-->
								<div class="col-sm-12 col-md-6">
									<div class="card">
										<div class="card-header">Video Views
											<div class="card-header-actions"></div>
										</div>
										<div class="card-body">
										<div class="row">
											<div class="col-sm-6 col-md-6">
												<div class="c-chart-wrapper">
													<canvas id="video-views-canvas-1"></canvas>
												</div>
											</div>
											<div class="col-sm-6 col-md-6">
												<div class="c-chart-wrapper">
													<canvas id="video-views-canvas-2"></canvas>
												</div>
											</div>
										</div>
										<div>
									</div>
									<!-- /.card-->
								</div>
								<!-- /.col-->
							</div>
							<!-- /.row-->

						</div>
					</div>

@endsection

@section('javascript')

<script src="{{ asset('js/Chart.min.js') }}"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script> -->
<script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script type="text/javascript">
let devicesDataRaw = [
	@foreach ($devicesData as $key => $data) 
		"{{$data}}",
	@endforeach];
let trafficDataRaw = [
	@foreach ($trafficData as $key => $data) 
		"{{$data}}",
	@endforeach];
let sessionDataRaw = [
	@foreach ($gaSessionGaphData['sessions'] as $key => $data) 
		"{{$data}}",
	@endforeach];
let sessionDurationDataRaw = [
	@foreach ($gaSessionGaphData['durations'] as $key => $data) 
		"{{$data}}",
	@endforeach];
Backend.Chart.deviceDoughnutChart.data.datasets[0].data = devicesDataRaw;
Backend.Chart.trafficDoughnutChart.data.datasets[0].data = trafficDataRaw;
Backend.Chart.sessionLineChart.data.datasets[0].data = sessionDataRaw;
Backend.Chart.sessionDurationLineChart.data.datasets[0].data = sessionDurationDataRaw;
Backend.Chart.init();

</script>
@endsection
