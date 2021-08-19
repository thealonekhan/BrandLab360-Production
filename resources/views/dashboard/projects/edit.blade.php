@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-10 col-lg-8 col-xl-6">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i> {{ __('Edit') }}: {{ $project->title }}</div>
                    <div class="card-body">
                        <form method="POST" action="/projects/{{ $project->id }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label>Title</label>
                                <input class="form-control" type="text" placeholder="{{ __('Title') }}" name="title" value="{{ $project->title }}" required autofocus>
                            </div>

                            <div class="form-group row">
                                <label>Main App ID</label>
                                <input class="form-control" type="text" placeholder="{{ __('Main Google Analytics App Id') }}" name="main_analytics_id" value="{{ $project->main_analytics_id }}" required>
                            </div>
                            
                            <div class="form-group row">
                                <label>Source App ID</label>
                                <input class="form-control" type="text" placeholder="{{ __('Source Google Analytics App Id') }}" name="source_analytics_id" value="{{ $project->source_analytics_id }}" required>
                            </div>

                            <div class="form-group row">
                                <label>Description</label>
                                <textarea class="form-control" id="textarea-input" name="description" rows="9" placeholder="{{ __('Description..') }}" >{{ $project->source_analytics_id }}</textarea>
                            </div>

                            <div class="form-group row">
                                <div class="col">
                                    <label>Status</label>
                                    <select class="form-control" name="status_id">
                                        @foreach($statuses as $status)
                                            @if( $status->id == $project->status_id )
                                                <option value="{{ $status->id }}" selected="true">{{ $status->name }}</option>
                                            @else
                                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
 
                            <button class="btn btn-block btn-success" type="submit">{{ __('Save') }}</button>
                            <a href="{{ route('projects.index') }}" class="btn btn-block btn-primary">{{ __('Return') }}</a> 
                        </form>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection

@section('javascript')

@endsection