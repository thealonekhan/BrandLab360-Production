@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-10 col-lg-8 col-xl-6">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i> Note: {{ $project->title }}</div>
                    <div class="card-body">
                        <br>
                        <dt>Title:</dt>
                        <p> {{ $project->title }}</p>
                        <dt>Analytics Project ID:</dt> 
                        <p>{{ $project->analytics_project_id }}</p>
                        <dt>Analytics View ID:</dt> 
                        <p>{{ $project->analytics_view_id }}</p>
                        <dt>Description:</dt> 
                        <p>{{ $project->description }}</p>
                        <dt>Status</dt>
                        <p>
                            <span class="{{ $project->status->class }}">
                              {{ $project->status->name }}
                            </span>
                        </p>
                        <dt>Created By:</dt>
                        <p> {{ $project->owner->name }}</p>
                        <a href="{{ route('projects.index') }}" class="btn btn-block btn-primary">{{ __('Return') }}</a>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')

@endsection