@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")


        <div class="col-md-10 col-md-offset-1" style="margin-top: 70px !important;">
            <div class="card card-block">
                <div class="col-md-12">
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

                <div>
                    <ul class="nav nav-pills">
                        <li class="active"><a href="#home" data-toggle="tab">Conditional Report</a></li>
                        <li><a href="#profile" data-toggle="tab">CheckIn/Out Report</a></li>
                    </ul>
                    <hr/>
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade active in" id="home">
                            <form method="post" id="crForm">
                                <div class="row">
                                    <div class="col-md-6 col-md-offset-3">
                                        <div class="form-group col-md-5">
                                            <label for="fname" class="col-lg-2 control-label">Start Date</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control date" name="start_date"
                                                       placeholder="Start Date">
                                            </div>
                                        </div>

                                        <div class="form-group col-md-5">
                                            <label for="fname" class="col-lg-2 control-label">End Date</label>
                                            <div class="col-lg-10">
                                                <input type="text" class="form-control date" name="end_date"
                                                       placeholder="End Date">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="submit" value="Search" class="btn btn-success">
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="crGrid"></div>
                        </div>

                        <div class="tab-pane fade" id="profile">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <form method="post" id="cicoForm">
                                        <div class="row">
                                            <div class="form-group col-md-5">
                                                <label for="fname" class="col-lg-2 control-label">Start Date</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control date" name="start_date"
                                                           placeholder="Start Date">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-5">
                                                <label for="fname" class="col-lg-2 control-label">End Date</label>
                                                <div class="col-lg-10">
                                                    <input type="text" class="form-control date" name="end_date"
                                                           placeholder="End Date">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="submit" value="Search" class="btn btn-success">
                                                <input type="button" id="cicoPrint" value="Print"
                                                       class="btn btn-default">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="cicogrid"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="/assets/js/custom/report.js"></script>

@endsection

