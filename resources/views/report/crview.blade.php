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
                    <legend></legend>
                    <div>
                        @if($schedule)
                            @foreach($schedule as $sc)
                                @foreach($sc->scheduledMaintenenceDate() as $scdate)
                                    @if($scdate->is_completed == true)
                                        <div class="card card-block">
                                            {{$scdate->maintenence_date}}
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
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

