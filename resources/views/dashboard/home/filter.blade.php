<div class="row mb-3">
    @if($filters->matrix == "on")
	<div class="col-sm-4 d-md-block mt-3">
		<select class="form-control" id="gamatric">
			<option value="5">User</option>
			<option value="1">Session</option>
			<option value="2">Bounce Rate</option>
			<option value="3">New user</option>
			<option value="4">Avg. Session Duration</option>
			<option value="8">Number of Sessions per User</option>
		</select>
	</div>
    @endif
	<div class="col-sm-8 d-md-block mt-2">
        @csrf
        @if($filters->datepicker == "on")
		<div class="btn-group btn-group-toggle float-right mb-0 mt-2" data-toggle="buttons">
			<input type="text" name="daterange" class="form-control" />
		</div>
        @endif
        @if($filters->quickDate == "on")
		
        <!-- <button class="btn btn-primary float-right" type="button">
        <svg class="c-icon">
        <use xlink:href="{{url('/assets/icons/coreui/free-symbol-defs.svg#cui-cloud-download')}}"></use>
        </svg>
        </button> -->
        <div class="btn-group btn-group-toggle float-right mr-3 mt-2" data-toggle="buttons">
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
        @endif
	</div>
</div>