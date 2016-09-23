@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")


        <div class="col-md-12" style="margin-top: 70px !important;">

            <div class="row">
                <div class="col-md-8 col-md-offset-2">
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

                </div>
            </div>
            <div class="col-md-10 card card-block col-md-offset-1">
                <fieldset>
                    <legend>{{$artefact->first()->artefact_name}}</legend>
                    <div>
                        @if($schedule)
                            @foreach($schedule as $sc)
                                <div class="col-md-3">
                                    <div class="card card-block">
                                        <h4 class="card-title">{{$sc->artefact_name}}</h4>
                                        <p class="card-text">
                                            <label>Done At </label>&nbsp;&nbsp;<span
                                                    class="label label-success">{{$sc->updated_at}}</span><br/>
                                            <label>Maintenence Date </label>&nbsp;&nbsp;<span
                                                    class="label label-success">{{$sc->maintenence_date}}</span><br/>
                                            <label>Created Date </label>&nbsp;&nbsp;<span
                                                    class="label label-success">{{$sc->created_at}}</span><br/>
                                        <div class="pull-right" style="float: right;">
                                            <a class="btn btn-danger"> Edit</a>
                                            <a class="btn btn-success" target="_blank" href="/crReportPrint/{{$sc->id}}"> Print</a>
                                        </div>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <center><h4>No Condition Report Found</h4></center>
                        @endif

                    </div>
                </fieldset>
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

