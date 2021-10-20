@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-10 col-lg-8 col-xl-6">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i> {{ __('Create') }}</div>
                    <div class="card-body">
                        <br>
                        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <input type="text" name="fakeemailremembered" style="display:none" id="email" />
                            <input type="password" name="fakepasswordremembered" style="display:none" id="password" />
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <svg class="c-icon c-icon-sm">
                                          <use xlink:href="/assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
                                      </svg>
                                    </span>
                                </div>
                                <input class="form-control" type="text" placeholder="{{ __('Name') }}" name="name" required autofocus autocomplete="off">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">@</span>
                                </div>
                                <input class="form-control" type="text" placeholder="{{ __('E-Mail Address') }}" name="email" required autocomplete="nope">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <svg class="c-icon c-icon-sm">
                                          <use xlink:href="/assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
                                      </svg>
                                    </span>
                                </div>
                                <input class="form-control" type="password" placeholder="{{ __('Password') }}" name="password" required autofocus autocomplete="new-password">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Role</span>
                                </div>
                              <select class="form-control" name="menuroles" id="menuroles">
                                  @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
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
                            <button class="btn btn-success pb-0" type="submit">{{ __('Save') }}</button>
                            <a href="{{ route('users.index') }}" class="btn btn-danger ml-1 pb-0">{{ __('Return') }}</a> 
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