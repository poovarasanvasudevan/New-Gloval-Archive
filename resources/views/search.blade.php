@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")


        <div class="col-md-12" style="margin-top: 60px !important;">
            <div class="card card-block">
                <div class="col-md-12">
                    <div class="col-md-6 col-md-offset-3">
                        @if (session()->has('flash_notification.message'))
                            <div class="alert alert-{{ session('flash_notification.level') }}">
                                <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>

                                {!! session('flash_notification.message') !!}
                            </div>
                        @endif

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
        </div>

        <div class="col-md-12">
            <div class="col-md-2 card card-block" style="height: 87% !important;">

            </div>
            <div class="col-md-10 card card-block" style="height: 87% !important;">

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/custom/search.js"></script>
@endsection

