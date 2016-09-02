@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")

        <div class="col-md-12 card card-block" style="padding: 10px !important;margin-top: 50px !important;">
            <div class="col-md-3"><input type="button" value="New Artefact" class="btn btn-success center-block" id="newArtefact"></div>
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
                                    <option value="0">select Artefact</option>
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
        <div>
            <div class="col-md-12">
                <div class="col-md-2 scroll scroll1 panel panel-default" style="height: 85% !important;overflow: auto;">
                    <div id="parent"></div>
                </div>
                <div class="col-md-10">
                    <form id="artefactForm" method="post" action="/saveArtefact">
                        <div class="panel panel-default"
                             style="height: 85% !important;overflow: auto;padding-right: 0px !important;">
                            <div class="panel-body">
                                <div class="card card-block" id="card-block">
                                    <div class="col-md-3">
                                        <h3 id="artefacttitle"></h3>
                                    </div>
                                    <div class="col-md-6"><center id="status"></center></div>
                                    <div class="col-md-3">
                                        <a href="" id="crlink" class="btn btn-info">Conditional Report</a>
                                        <input type="submit" value="Save" class="btn btn-primary">
                                        <a href="" id="printa" target="_blank" class="btn btn-info">Print</a>
                                    </div>
                                </div>
                                <div id="detailPanel" class="card card-block"
                                     style="height: 84% !important;overflow: auto;padding-right: 0px !important;">
                                    <center style="margin-top: 23% !important;">
                                        <img src="/image/logo.png" height="150" width="150">
                                    </center>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')

    <script src="/assets/js/definition.js"></script>
@endsection