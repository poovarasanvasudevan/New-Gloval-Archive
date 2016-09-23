@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")


        <div class="col-md-12" style="margin-top: 70px !important;">

            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    @if($errors->any())
                        <div class="alert alert-dismissible alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session()->has('flash_notification.message'))
                        <div class="alert alert-{{ session('flash_notification.level') }}">
                            <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>

                            {!! session('flash_notification.message') !!}
                        </div>
                    @endif

                </div>
            </div>
            <div class="col-md-12">
                <div class="col-md-2">
                    <div class="card">
                        <img class="card-img-top img-thumbnail" src="{{Gravatar::src($user->email,300)}}"
                             alt="Card image cap">
                        <div class="card-block">
                            <h4 class="card-title">{{$user->fname}} {{ $user->lname }}</h4>
                            <a href="/reset-password" class="btn btn-danger btn-block">Reset Password</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="card card-block">
                        <div class="card card-block">
                            <center><h3 class="">My Profile</h3></center>
                            <a href="/reset-password" class="btn btn-danger pull-right">Reset Password</a>

                        </div>
                        <br/>
                        <br/>
                        <div class="col-md-6">
                            <form class="form-horizontal" method="post" id="newUser" action="/myUserUpdate" style="margin-bottom: 30px !important;">
                                <div class="form-group">
                                    <label for="fname" class="col-lg-2 control-label">First Name</label>
                                    <div class="col-lg-10">
                                        <input type="text" required class="form-control" name="fname" id="fname"
                                               placeholder="First Name"
                                               value="{{$user->fname}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lname" class="col-lg-2 control-label">Last Name</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="lname" required class="form-control" id="lname"
                                               placeholder="Last Name"
                                               value="{{$user->lname}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="abhayasiId" class="col-lg-2 control-label">Abhyasi Id</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" required id="abhayasiId"
                                               name="abhayasiId"
                                               value="{{$user->abhyasiid}}"
                                               placeholder="Abhyasi Id">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-lg-2 control-label">Email</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" required id="email" name="email"
                                               placeholder="Email"
                                               value="{{$user->email}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-lg-2 control-label">Location</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control"  id="email" name="location"
                                               placeholder="Location"
                                               disabled
                                               value="{{\App\Location::find($user->location)->long_name}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-lg-2 control-label">Archive Location</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control"  id="email" name="alocation"
                                               placeholder="Location"
                                               disabled
                                               value="{{\App\Location::find($user->archive_location)->long_name}}">
                                    </div>
                                </div>

                                <div>
                                    <input type="submit" value="Update Profile" class="btn pull-right btn-success">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {

        })
    </script>
@endsection

