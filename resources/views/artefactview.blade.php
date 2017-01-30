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
                                        @if($at->html_type == 'file')
                                            @if($artefact->hasMedia())
                                                <div class="card carousel row padding15" id="mediaCard">
                                                    @foreach($artefact->getMedia() as $media)
                                                        <div class="col-md-3">
                                                            @if($media->getExtensionAttribute() == 'xlsx' || $media->getExtensionAttribute() == 'xls' || $media->getExtensionAttribute() == '.csv')

                                                                <div class="card cc{{$media->id}}">
                                                                    <img class="card-img-top img-responsive" src="/assets/images/Excel-icon.png" alt="Card image cap">
                                                                    <div class="card-block">
                                                                        <a href="{{$media->getUrl()}}" target="_blank" class="btn btn-block btn-success">
                                                                            <i class="fa fa-file-excel-o"></i>
                                                                        </a>

                                                                    </div>
                                                                </div>
                                                            @elseif($media->getExtensionAttribute() == 'pdf')

                                                                <div class="card cc{{$media->id}}">
                                                                    <img class="card-img-top img-responsive" src="/assets/images/pdf-icon-1.png" alt="Card image cap">
                                                                    <div class="card-block">
                                                                        <a href="{{$media->getUrl()}}" target="_blank" class="btn btn-block btn-success">
                                                                            <i class="fa fa-file-pdf-o"></i>
                                                                        </a>

                                                                    </div>
                                                                </div>

                                                            @else
                                                                <div class="card cc{{$media->id}}">

                                                                    <img class="card-img-top img-responsive" src="/assets/images/Docs-icon.png" alt="Card image cap">
                                                                    <div class="card-block">
                                                                        <a href="{{$media->getUrl()}}" target="_blank" class="btn img-media btn-block btn-success">
                                                                            <i class="fa fa-file-image-o"></i>
                                                                        </a>

                                                                    </div>
                                                                </div>

                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @else
                                            <p>{{\App\Helpers\MyHelper::getAttrValues($artefact->id,$at->id)}}</p>
                                        @endif
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
            $('.carousel').carousel({
                show: 3
            });
            $('.img-media').lightbox();

        })
    </script>
@endsection

