@extends('layout.layout2')
@section('content')
    <div class="full-div">
        @include('layout.adminnav')
        <div style="margin-top: 70px !important;">
            <div class="col-md-12">
                <div class="col-md-2">
                    @include('admin.sidebar')
                </div>
                <div class="col-md-10 card card-block" style="height: 90% !important;">
                    @if (session()->has('flash_notification.message'))
                        <div class="alert alert-{{ session('flash_notification.level') }}">
                            <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>

                            {!! session('flash_notification.message') !!}
                        </div>
                    @endif
                    <div class="col-md-6">
                        <fieldset class="fieldset">
                            <legend class="legend1">Cico Mail Configuration</legend>
                            <div>
                                <div class="col-md-12">
                                    <form method="post" action="/admin/saveCicoMail">
                                        <div>
                                            <textarea class="form-control" rows="5" name="cico_mail">
                                                @if(is_array($cico_mail))
                                                    @foreach($cico_mail as $mail)
                                                        {{$mail.","}}
                                                    @endforeach
                                                @endif
                                            </textarea>
                                        </div>
                                        <br/>
                                        <div class="pull-left">
                                            <b>
                                                <small>For multiplse mail seperated by ( , ) comma</small>
                                            </b>
                                        </div>
                                        <div class="pull-right">

                                            <input type="submit" value="Save Configuration" id="save"
                                                   class="btn btn-success">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-md-6">
                        <fieldset class="fieldset">
                            <legend class="legend1">Scheduler Configuration</legend>
                            <div>
                                <ul>
                                    <li>
                                        <div>
                                            <p>Dispatch Scheduler Notification that executed once a day</p>
                                            <a href="/admin/notify" class="btn btn-danger">Dispatch Schedule
                                                Notification</a>
                                        </div>
                                        <br/>
                                    </li>
                                    <li>
                                        <div>
                                            <p>Site Up Or Down</p>
                                            @if(App::isDownForMaintenance())
                                                <b>Status : </b> <label class="label label-danger">Maintenence Mode</label>
                                            <br/>
                                            <br/>
                                                <a href="/admin/up" class="btn btn-danger">Site Up</a>
                                            @else
                                                <b>Status : </b> <label class="label label-success">Running</label>
                                                <br/>
                                                <br/>
                                                <a href="/admin/down" class="btn btn-danger">Site Down</a>
                                            @endif
                                        </div>
                                    </li>

                                    <li>
                                        <div>
                                            <p>Clear Cache</p>
                                            <a href="/admin/cacheclear" class="btn btn-info">Clear Cache</a>
                                        </div>
                                        <br/>
                                    </li>
                                </ul>


                            </div>
                        </fieldset>
                    </div>

                    <div class="col-md-6">
                        <fieldset class="fieldset">
                            <legend class="legend1">Version</legend>
                            <div>
                                <form method="post" action="/admin/setVersion">
                                    <div class="form-group">
                                        <label for="artefactTypes">Version</label>
                                        <input type="number" name="version" class="form-control" value="{{$version}}">
                                    </div>
                                    <input type="submit" value="Save Version" class="btn btn-success pull-right">
                                </form>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>

    </script>
@endsection