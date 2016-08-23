@extends('layout.layout')

@section('content')
    <div class="full-bg">
        <div class="col-md-4 col-md-offset-7">
            <form class="form-horizontal center-form" method="post" action="/login">

                <div class="panel panel-default padding15">
                    <div class="panel-body">
                        @if($errors->any())
                            <div class="alert alert-dismissible alert-danger">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>{{$errors->first()}}</strong>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="inputEmail" class="col-lg-2 control-label">Abhyasi Id</label>
                            <div class="col-lg-10">
                                <input class="form-control" name="username" id="inputEmail" placeholder="Abhyasi Id"
                                       type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="col-lg-2 control-label">Password</label>
                            <div class="col-lg-10">
                                <input class="form-control" name="password" id="inputPassword" placeholder="Password"
                                       type="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="/forget" class="padding15 pull-right">Forget
                                Password</a>
                            <input type="submit" value="Login" class="btn btn-success pull-right"
                                   style="margin-right: 15px; !important;">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection