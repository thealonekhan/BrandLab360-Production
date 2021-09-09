@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-10 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i>{{ $project->title }}</div>
                    <div class="card-body">
                        <table class="table table-responsive-sm table-striped">
                        <tbody>
                          <tr>
                            <td><strong>Project Title</strong></td>
                            <td>{{ $project->title }}</td>
                          </tr>
                          <tr>
                            <td><strong>Analytics Project ID</strong></td>
                            <td>{{ $project->analytics_project_id }}</td>
                          </tr>
                          <tr>
                            <td><strong>Analytics View ID</strong></td>
                            <td>{{ $project->analytics_view_id }}</td>
                          </tr>
                          <!-- <tr>
                            <td><strong>Description</strong></td>
                            <td>{{ $project->description }}</td>
                          </tr> -->
                          <tr>
                            <td><strong>Status</strong></td>
                            <td><span class="{{ $project->status->class }}">
                              {{ $project->status->name }}
                            </span>
                            </td>
                          </tr>
                          <tr>
                            <td><strong>Created By</strong></td>
                            <td>{{ $project->owner->name }}</td>
                          </tr>
                        </tbody>
                        </table>

                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')

@endsection