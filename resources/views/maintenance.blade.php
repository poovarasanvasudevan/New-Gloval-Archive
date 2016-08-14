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
                <div class="card card-block" id="schedulePanel" style="height: 60px !important;">

                    <div class="col-md-3" style="padding: 0px !important;">
                        <h3 id="artefact_name"></h3>
                    </div>
                    <div class="pull-right col-md-2 col-md-offset-7">
                        <input type="button" class="btn btn-info" value="Perodic">
                        <input type="button" class="btn btn-warning" value="Sperodic">
                    </div>

                </div>
                <div id="content" style="height: 88% !important;overflow: auto;">


                </div>
            </div>

        </div>
    </div>

@endsection

@section('js')
    <script src="/assets/js/custom/maintenence.js"></script>
@endsection