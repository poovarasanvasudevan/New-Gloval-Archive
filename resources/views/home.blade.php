@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")


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

            @foreach($artefacttypes as $artefacttype)
                <div class="col-md-3">
                    <div class="card card-block" title="{{$artefacttype->artefact_description}}">
                        <h4 class="card-title">{{$artefacttype->artefact_type_long}}</h4>
                        <p class="card-text">{{$artefacttype->artefact_description}}</p>
                        <div class="pull-right">
                            <a href="/search/{{$artefacttype->id}}" class="btn btn-danger" title="Search Artefacts">Search Artefacts</a>
                            <a href="/task" class="btn btn-success" title="View Schedules">View Schedules</a>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    </div>
@endsection