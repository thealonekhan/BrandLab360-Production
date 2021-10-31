@extends('dashboard.base')

@section('content')
<div class="container-fluid">
    <div class="fade-in">
        <div class="row">
            <div class="col-sm-4 d-md-block">
                <div class="card text-center">
                    <div class="card-header custom-card-header">Realtime</div>
                    <div class="card-body">

                        <h2>Right Now</h2>
                        <h1>{{$realtime['active_users']}}</h1>
                        <p>active users on site</p>
                    </div>
                    <div class="card-footer">
                        {{\Carbon\Carbon::now()}}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>

    .custom-card-header{
        background-color: #525256;
        border-color: #2a2a2a;
        color: #FFFFFF;
    }

</style>

@endsection