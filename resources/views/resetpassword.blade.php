@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")
        <div class="col-md-4 col-md-offset-4" style="margin-top: 10% !important;">
            <div class="panel panel-default padding15">
                <form method="post" action="/resetmypassword">
                    <div class="panel-body">
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

                        <div>
                            <div class="form-group">
                                <label for="currentPassword" class="col-lg-2 control-label">Current Password</label>
                                <div class="col-lg-10">
                                    <input class="form-control" name="currentPassword" id="currentPassword"
                                           placeholder="Current Password"
                                           type="password">
                                </div>
                            </div>
                            <br/>
                            <br/>
                            <br/>

                            <div class="form-group">
                                <label for="newPassword" class="col-lg-2 control-label">New Password</label>
                                <div class="col-lg-10">
                                    <input class="form-control" name="newPassword" id="newPassword"
                                           placeholder="New Password"
                                           type="password">
                                </div>
                            </div>

                            <br/>
                            <br/>

                            <div class="form-group">
                                <label for="renewPassword" class="col-lg-2 control-label">Re Enter Password</label>
                                <div class="col-lg-10">
                                    <input class="form-control" name="renewPassword" id="renewPassword"
                                           placeholder="Re Enter Password"
                                           type="password">
                                </div>
                            </div>

                            <br/>

                            <div class="padding15">
                                <input type="submit" class="btn btn-primary pull-right" value="Reset">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
@endsection