@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">

              <!-- <div class="col-sm-6 col-md-5 col-lg-4 col-xl-3">
                <div class="card">
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i> User {{ $user->name }}</div>
                    <div class="card-body">
                        <h4>Name: {{ $user->name }}</h4>
                        <h4>E-mail: {{ $user->email }}</h4>
                        <a href="{{ route('users.index') }}" class="btn btn-block btn-primary">{{ __('Return') }}</a>
                    </div>
                </div>
              </div> -->

                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header"><i class="fa fa-align-justify"></i> User {{ $user->name }}</div>
                    <div class="card-body">
                      <table class="table table-responsive-sm table-striped">
                        <tbody>
                          <tr>
                            <td><strong>Name</strong></td>
                            <td>{{ $user->name }}</td>
                          </tr>
                          <tr>
                            <td><strong>E-mail</strong></td>
                            <td>{{ $user->email }}</td>
                          </tr>
                          <tr>
                            <td><strong>Projects</strong></td>
                            <td>
                            @if(!empty($projects->project))
                              <span class="badge badge-success">{{$projects->project->title}}</span>
                            @endif
                            </td>
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