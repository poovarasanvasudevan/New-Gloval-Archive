@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")


        <div class="col-md-12" style="margin-top: 60px !important;">
            <div class="card card-block">
                <div class="col-md-12">
                    <div class="col-md-3">
                        <input type="button" id="search" class="btn btn-success" value="Search">
                        <a class="btn btn-danger" href="/search/0">Reset</a>
                    </div>
                    <div class="col-md-6">
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
            <div class="col-md-2 card card-block" style="height: 87% !important;overflow-y: auto;">
                @if($attributes)
                    <form id="searchAttr">
                        <input type="hidden" value="{{$attr_id}}" name="artefact_type">
                        @foreach($attributes as $attribute)

                            <div class="card card-block">
                                <p class="card-title">{{$attribute->attribute_title}}</p>
                                <div>
                                    @if($attribute->html_type =='text')
                                        <input type="text" class="form-control" name="{{$attribute->id}}"
                                               id="{{$attribute->id}}">
                                    @elseif($attribute->html_type=='number')
                                        <input type="number" class="form-control" name="{{$attribute->id}}"
                                               id="{{$attribute->id}}">
                                    @elseif($attribute->html_type=='textarea')
                                        <textarea class="form-control" rows="4" cols="5" name="{{$attribute->id}}"
                                                  id="{{$attribute->id}}"> </textarea>
                                    @elseif($attribute->html_type=='select')
                                        <input type="text" class="form-control autocomplete" name="{{$attribute->id}}"
                                               id="{{$attribute->id}}">
                                    @elseif($attribute->html_type=='date')
                                        <input type="text" class="form-control date" name="{{$attribute->id}}"
                                               id="{{$attribute->id}}">
                                    @elseif($attribute->html_type=='file')
                                        <input type="file" class="form-control" id="{{$attribute->id}}">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </form>
                @else
                    <center><label>No Attributes Found</label></center>
                @endif
            </div>
            <div id="topBlock" class="col-md-10 card card-block" style="height: 87% !important;">
                <div class="card card-block">
                    <div class="pull-right">
                        <input type="button"  id="next" class="btn btn-primary" value="Next >>">
                    </div>
                    <div class="pull-left">
                        <input type="button" id="prev" class="btn btn-primary" value="<< Prev">
                    </div>
                </div>
                <div id="searchTable" class="card card-block" style="height: 92% !important;">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/assets/js/custom/datatable.js"></script>
    <script src="/assets/js/custom/search.js"></script>
@endsection

