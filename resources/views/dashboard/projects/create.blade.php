@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-10 col-lg-8 col-xl-6">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i> {{ __('Create Project') }}</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('projects.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label>Title</label>
                                <input class="form-control" type="text" placeholder="{{ __('Title') }}" name="title" required autofocus>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 pl-0 pr-0">
                                  <label>Analytics Project ID</label>
                                  <a data-toggle="popover-project" class="project-popover float-right" href="#"><i class="c-icon c-icon-lg cil-info"></i></a>
                                  <input class="form-control border-danger" data-inputmask="" type="text" placeholder="{{ __('Analytics Project Id') }}" name="analytics_project_id" id="analytics_project_id" required>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                              <div class="col-md-12 pl-0 pr-0">
                                  <label>Analytics View ID</label>
                                  <a data-toggle="popover-project" class="project-popover float-right" href="#"><i class="c-icon c-icon-lg cil-info"></i></a>
                                  <input class="form-control border-success" data-inputmask="" type="text" placeholder="{{ __('Analytics View Id') }}" name="analytics_view_id" id="analytics_view_id" required>
                              </div>
                            </div>

                            <!-- <div class="form-group row">
                                <label>Description</label>
                                <textarea class="form-control" id="textarea-input" name="description" rows="9" placeholder="{{ __('Description..') }}" ></textarea>
                            </div> -->

                            <div class="form-group row">
                                <label>Status</label>
                                <select class="form-control" name="status_id">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group row">
                              <button class="btn btn-success" type="submit">{{ __('Save') }}</button>
                              <a href="{{ route('projects.index') }}" class="btn btn-danger ml-1">{{ __('Return') }}</a>  
                            </div>
                            <!-- <button class="btn btn-sm btn-danger float-right ml-2" type="reset"> Reset</button> -->
                            <!-- <button class="btn btn-sm btn-success float-right" type="submit"> Save</button> -->
                        </form>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

<style>
  .popover,.popover-body{
    width: 40rem;
    max-width: 40rem;
  }

  .cil-info{
    color: #39f;
  }
</style>

@endsection

@section('javascript')
<script type="text/javascript">

$(document).ready(function(){

  $("#analytics_project_id").inputmask('UA-999999999-9');
  $("#analytics_view_id").inputmask({mask: '999999999'});

});
</script>

@endsection