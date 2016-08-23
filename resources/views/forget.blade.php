@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="/">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="col-md-12" style="margin-top: 70px !important;">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    @if (session()->has('flash_notification.message'))
                        <div class="alert alert-{{ session('flash_notification.level') }}">
                            <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>

                            {!! session('flash_notification.message') !!}
                        </div>
                    @endif

                </div>
            </div>

            <div class="col-md-4 col-md-offset-4">
                <form method="post" action="/resetPassword">
                    <div class="card card-block">
                        <div class="form-group">
                            <label for="email" class="col-lg-2 control-label">Email</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" required id="email" name="email"
                                       placeholder="Email"
                                       value="">
                            </div>
                        </div>
                        <br/>
                        <br/>

                        <div class="form-group">
                            <label for="location" class="col-lg-2 control-label">Abhyasi ID</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control" required id="abhyasiid" name="abhyasiid"
                                       placeholder="Abhyasi Id"
                                       value="">
                            </div>
                        </div>
                        <br/>
                        <br/>
                        <div class="pull-right">
                            <input type="submit" value="Reset" class="btn btn-success">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection