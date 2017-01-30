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
            <div class="col-md-8 card card-block col-md-offset-2">
                <div class="row">
                    <div class="form-group has-success col-md-6">
                        <label class="control-label" for="inputcheckin">List Task</label>
                        <div class="input-group">
                            <select class="form-control" id="type">
                                <option value="0">--Select--</option>
                                <option value="1">This Week</option>
                                <option value="2">This Month</option>
                                <option value="3">This Year</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr/>

                <div class="col-md-12" style="margin: 0px !important;padding: 0px !important;">
                    @if($result)
                        @foreach($result as $r)
                            <div class="col-md-4">
                                <div class="card card-block">
                                    <h3 class="card-title">{{$r->artefact_name}}</h3>
                                    <p class="card-text">
                                        {{$r->artefact_type_long}}
                                        <span class="label label-info">{{$r->maintenence_date}}</span>
                                        <br/>
                                        {{$r->maintenence_description}}
                                    </p>

                                    @if(\Carbon\Carbon::createFromFormat("Y-m-d",$r->maintenence_date)->diffInDays(\Carbon\Carbon::now(),false)>=0)

                                        <a type="button" class="btn btn-success pull-right" href="/doTask/{{$r->id}}">Make
                                            Report Now</a>
                                    @else

                                        <a type="button" class="btn btn-success pull-right" disabled>Make Report Now</a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <center><label>No Schedule Found</label></center>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            $('#type').change(function () {
                window.location = '/task/' + $(this).val();
            })
        })
    </script>
@endsection

