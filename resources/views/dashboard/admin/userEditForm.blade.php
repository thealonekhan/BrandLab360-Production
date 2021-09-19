@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-6 col-md-5 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i> {{ __('Edit') }} {{ $user->name }}</div>
                    <div class="card-body">
                        <br>
                        <form method="POST" action="/users/{{ $user->id }}">
                            @csrf
                            @method('PUT')
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <svg class="c-icon c-icon-sm">
                                          <use xlink:href="/assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
                                      </svg>
                                    </span>
                                </div>
                                <input class="form-control" type="text" placeholder="{{ __('Name') }}" name="name" value="{{ $user->name }}" required autofocus>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@</span>
                                </div>
                                <input class="form-control" type="text" placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ $user->email }}" required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <svg class="c-icon c-icon-sm">
                                          <use xlink:href="/assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
                                      </svg>
                                    </span>
                                </div>
                                <input class="form-control" type="password" placeholder="{{ __('Password') }}" name="password">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Role</span>
                                </div>
                              <select class="form-control" name="menuroles" id="menuroles">
                                  @foreach($roles as $role)
                                      @if($role->name == $user->menuroles)
                                          <option value="{{ $user->menuroles }}" selected>{{ $user->menuroles }}</option>
                                      @else
                                          <option value="{{ $role->name }}">{{ $role->name }}</option>
                                      @endif
                                  @endforeach
                                </select>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Project</span>
                                </div>
                              <select class="form-control" name="project_id" id="project">
                                @if(!empty($projects->project))
                                  <option value="{{ $projects->project->id }}">{{ $projects->project->title }}</option>
                                @else
                                  @foreach($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                                  @endforeach
                                @endif
                                </select>
                            </div>
                            <button class="btn btn-success" type="submit">{{ __('Save') }}</button>
                            <a href="{{ route('users.index') }}" class="btn btn-danger ml-1">{{ __('Return') }}</a> 
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