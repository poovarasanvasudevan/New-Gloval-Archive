@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")

        <div class="col-md-12 card card-block" style="margin-top: 50px !important;padding-top: 5px !important;">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fname" class="col-lg-2 control-label">Archive Location</label>
                            <div class="col-lg-10">
                                <select class="form-control">
                                    <option value="0">select Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->long_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fname" class="col-lg-2 control-label">Artefact Types</label>
                            <div class="col-lg-10">
                                <select class="form-control" id="artefactTypes">
                                    <option value="0">select Location</option>
                                    @foreach($artefacttypes as $artefacttype)
                                        <option value="{{$artefacttype->id}}">{{$artefacttype->artefact_type_long}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-2 card card-block"
                 style="height: 86% !important;overflow: auto; padding:0px !important;">
                <div id="parent"></div>
            </div>

            <div class="col-md-10 card card-block"
                 style="height: 86% !important;overflow: auto; padding:0px !important;">
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
                <div class="card card-block" id="schedulePanel" style="height: 60px !important;">

                    <div class="col-md-3" style="padding: 0px !important;">

                        <h3 id="artefact_name"></h3>
                    </div>
                    <div class="pull-right col-md-2 col-md-offset-7">
                        <a id="periodicOpener" href="#newPerodicMaintenance" class="btn btn-info">Perodic</a>
                        <form id="newPerodicMaintenance" class="white-popup-block mfp-hide" method="post"
                              action="/addPerodicMaintenance">
                            <fieldset>
                                <input type="hidden" name="artefact_id" value="" id="artefact_id" class="artefact_id">
                                <legend>New Sperodic Maintenence</legend>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname" class="col-lg-3 control-label">Start Date</label>
                                        <div class="col-lg-9">

                                            <input type="text" required class="form-control scheduleDate"
                                                   name="start_date" id="start_date"
                                                   placeholder="Start Date"
                                                   value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname" class="col-lg-3 control-label">End Date</label>
                                        <div class="col-lg-9">
                                            <input type="text" required class="form-control scheduleDate"
                                                   name="end_date" id="end_date"
                                                   placeholder="End Date"
                                                   value="">
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <br/>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname" class="col-lg-3 control-label">Occur</label>
                                        <div class="col-lg-9">

                                            <input type="number" required class="form-control"
                                                   name="occurance_number" id="occurance_number"
                                                   placeholder="Occurance"
                                                   value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname" class="col-lg-3 control-label">Every</label>
                                        <div class="col-lg-9">
                                            <select class="form-control occurtype" required name="type">
                                                <option value="0">--select--</option>
                                                <option value="week">Week</option>
                                                <option value="month">Month</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <br/>
                                <br/>
                                <br/>
                                <div class="col-md-12 weekdays">
                                    <div class="form-group">
                                        <label for="fname" class="col-lg-1 control-label">Day</label>
                                        <div class="col-lg-11">
                                            <select class="form-control" name="weekdays" id="weekdays">
                                                <option value="0">--select--</option>
                                                <option value="1">Sunday</option>
                                                <option value="2">Monday</option>
                                                <option value="3">Tuesday</option>
                                                <option value="4">Webnesday</option>
                                                <option value="5">Thursday</option>
                                                <option value="6">Friday</option>
                                                <option value="7">Saturday</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 monthdays">
                                    <div class="form-group">
                                        <label for="fname" class="col-lg-1 control-label">Day</label>
                                        <div class="col-lg-11">

                                            <input type="number" class="form-control"
                                                   name="month_day" id="month_day"
                                                   placeholder="Day"
                                                   value="">
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <br/>
                                <br/>
                                <div class="form-group">
                                    <label for="fname" class="col-lg-2 control-label">Description</label>
                                    <div class="col-lg-10">
                                        <textarea disabled name="maintenenceDesc" id="maintenenceDesc"
                                                  class="form-control" rows="5"></textarea>
                                    </div>
                                </div>

                                <hr/>
                                <br/>
                                <div>
                                    <input type="submit" value="Create" class="btn btn-success pull-right">
                                </div>
                            </fieldset>
                        </form>
                        <a id="sperodicOpener" href="#newSperodicMaintenance" class="btn btn-warning">Sperodic</a>
                        <form id="newSperodicMaintenance" class="white-popup-block mfp-hide" method="post"
                              action="/addSperodicMaintenance">
                            <fieldset>
                                <legend>New Sperodic Maintenence</legend>
                                <div class="form-group">
                                    <label for="fname" class="col-lg-2 control-label">Schedule Date</label>
                                    <div class="col-lg-10">
                                        <input type="hidden" name="artefact_id" value="" id="artefact_id" class="artefact_id">
                                        <input type="text" required class="form-control scheduleDate"
                                               name="scheduleDate" id="scheduleDate"
                                               placeholder="Schedule Date"
                                               value="">
                                    </div>
                                </div>
                                <br/>
                                <br/>

                                <div class="form-group">
                                    <label for="fname" class="col-lg-2 control-label">Description</label>
                                    <div class="col-lg-10">
                                        <textarea disabled name="maintenenceDesc" id="maintenenceDesc"
                                                  class="form-control" rows="5"></textarea>
                                    </div>
                                </div>

                                <hr/>
                                <br/>
                                <br/>
                                <br/>
                                <div>
                                    <input type="submit" value="Create" class="btn btn-success pull-right">
                                </div>
                            </fieldset>
                        </form>
                    </div>

                </div>
                <div id="content" class="col-md-12" style="height: 88% !important;overflow: auto;">


                </div>
            </div>

        </div>
    </div>



@endsection

@section('js')
    <script src="/assets/js/custom/maintenence.js"></script>
@endsection