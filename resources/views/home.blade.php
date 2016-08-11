@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")


        <div class="col-md-12" style="margin-top: 70px !important;">
            @foreach($artefacttypes as $artefacttype)
                <div class="col-md-3">
                    <div class="card card-block" title="{{$artefacttype->artefact_description}}">
                        <h4 class="card-title">{{$artefacttype->artefact_type_long}}</h4>
                        <p class="card-text">{{$artefacttype->artefact_description}}</p>
                        <a href="#" class="btn btn-primary pull-right" title="View Schedules">View Schedules</a>
                    </div>
                </div>

            @endforeach
        </div>
    </div>
@endsection