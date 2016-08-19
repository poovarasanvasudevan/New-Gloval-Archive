@extends('layout.layout2')
@section('content')
    <div class="full-div">
        @include('layout.adminnav')
        <div style="margin-top: 70px !important;">
            @if (session()->has('flash_notification.message'))
                <div class="alert alert-{{ session('flash_notification.level') }}">
                    <button type="button" class="close" data-dismiss="alert"
                            aria-hidden="true">&times;</button>

                    {!! session('flash_notification.message') !!}
                </div>
            @endif
            <div class="col-md-12">
                <div class="col-md-2">
                    @include('admin.sidebar')
                </div>
                <div class="col-md-10 card card-block" style="height: 92% !important;">
                    <div class="col-md-4">
                        <div class="card card-block">
                            <h3 class="card-title">{{$user}} , Total Users</h3>
                            <p class="card-text">All Users in the Application</p>
                            <a href="#" class="btn btn-primary">View Them</a>
                        </div>

                    </div>
                    <div class="col-md-4">

                        <div class="card card-block">
                            <h3 class="card-title">{{$artefact}} , Total Artefact</h3>
                            <p class="card-text">All Artefact in the Application including parent and child</p>
                            <a href="#" class="btn btn-primary">View Them</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection