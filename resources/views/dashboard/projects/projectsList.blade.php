@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i>{{ __('Projects') }}</div>
                    <div class="card-body">
                        <div class="row"> 
                          <a href="{{ route('projects.create') }}" class="btn btn-primary m-2 custom-btn-color ml-3">{{ __('Add Project') }}</a>
                        </div>
                        <br>
                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th>Title</th>
                            <th>Analytics Project ID</th>
                            <th>Analytics View ID</th>
                            <th>Status</th>
                            <th></th>
                            <th></th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($projects as $project)
                            <tr>
                              <td><strong>{{ $project->title }}</strong></td>
                              <td>{{ $project->analytics_project_id }}</td>
                              <td>{{ $project->analytics_view_id }}</td>
                              <td>
                                  <span class="{{ $project->status->class }}">
                                      {{ $project->status->name }}
                                  </span>
                              </td>
                              <td>
                                <a href="{{ url('/projects/' . $project->id) }}" class="btn btn-block btn-primary custom-btn-color">View</a>
                              </td>
                              <td>
                                <a href="{{ url('/projects/' . $project->id . '/edit') }}" class="btn btn-block btn-primary custom-btn-color">Edit</a>
                              </td>
                              <td>
                                <a id="modal-delete-btn" data-attr="{{ route('projects.delete', $project->id ) }}" class="btn btn-block btn-danger pb-0">Delete</a>
                                <!-- <form action="{{ route('projects.destroy', $project->id ) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-block btn-danger pb-0" data-toggle="modal" data-target="#dangerModal">Delete</button>
                                </form> -->
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                      {{ $projects->links() }}
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')

@endsection

