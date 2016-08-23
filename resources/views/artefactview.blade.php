@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")


        <div class="col-md-8 col-md-offset-2" style="margin-top: 70px !important;">
            <div class="card card-block">
                <div class="pull-right">
                    <a href="/artefactprint/{{$artefact->id}}" class="btn btn-success">Print</a>
                </div>
            </div>
            <div class="card card-block">

                <div class="pull-left col-md-6">
                    <label>Artefact Name : </label> {{$artefact->artefact_name}} <br/>
                    <label>Created Date : </label> {{$artefact->created_at->toDateString()}}
                </div>
                <div class="pull-right col-md-6">
                    <label>Created By : </label> {{$artefact->user->fname}} {{$artefact->user->lname}} <br/>
                    <label>Location : </label> {{\App\Location::find($artefact->location)->long_name}}
                </div>
            </div>

            <div class="card card-block">


                @if($attr)
                    <fieldset class="fieldset">
                        <legend class="legend1">{{$artefact->artefact_name}}</legend>
                        <div>
                            @foreach($attr as $at)
                                <div class="row" style="padding: 10px;">
                                    <div class="col-md-6">
                                        <label><p class="text-right">{{$at->attribute_title}}</p></label>
                                    </div>
                                    <div class="col-md-6">
                                        <p>{{\App\Helpers\MyHelper::getAttrValues($artefact->id,$at->id)}}</p>
                                    </div>
                                </div>

                            @endforeach
                        </div>
                    </fieldset>
                @else
                @endif
            </div>
        </div>

    </div>
@endsection

@section('js')

    <script>
        $(function () {

        })
    </script>
@endsection

