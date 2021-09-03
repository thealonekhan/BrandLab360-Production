<div class="row">
	<div class="col-sm-4 d-md-block">
		<select class="form-control" id="gamatric">
			<option value="5">User</option>
			<option value="1">Session</option>
			<option value="2">Bounce Rate</option>
			<option value="3">New user</option>
			<option value="4">Avg. Session Duration</option>
		</select>
	</div>
	<div class="col-sm-8 d-md-block">
		<div class="btn-group btn-group-toggle float-right mb-3" data-toggle="buttons">
			<input type="text" name="daterange" class="form-control" />
		</div>
		@csrf
        <!-- <button class="btn btn-primary float-right" type="button">
        <svg class="c-icon">
        <use xlink:href="{{url('/assets/icons/coreui/free-symbol-defs.svg#cui-cloud-download')}}"></use>
        </svg>
        </button> -->
        <div class="btn-group btn-group-toggle float-right mr-3" data-toggle="buttons">
            <label class="btn btn-outline-secondary active">
                <input id="option1" type="radio" name="options" autocomplete="off" value="ga:date" checked> Day
            </label>
            <label class="btn btn-outline-secondary">
                <input id="option2" type="radio" name="options" autocomplete="off" value="ga:yearMonth"> Month
            </label>
            <label class="btn btn-outline-secondary">
                <input id="option3" type="radio" name="options" autocomplete="off" value="ga:year"> Year
            </label>
        </div>
	</div>
</div>