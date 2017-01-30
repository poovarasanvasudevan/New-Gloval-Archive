@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")


        <div class="col-md-10 col-md-offset-1" style="margin-top: 70px !important;">
            <div class="card card-block">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <a  class="btn btn-primary"
                               id="newRole" href="/editRole/0">New Role</a>
                        <a class="btn btn-success" href="/newUser"
                           id="newUser">New User</a>
                    </div>
                    <div class="col-md-8">
                        @if (session()->has('flash_notification.message'))
                            <div class="alert alert-{{ session('flash_notification.level') }}">
                                <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>

                                {!! session('flash_notification.message') !!}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            {!! $grid !!}
        </div>
    </div>
@endsection

@section('js')

    <script>
        $(function () {

        })
    </script>
@endsection

