@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")

        <div class="col-md-12" style="margin-top: 70px !important;">

            <div class="col-md-3">

                <div class="card padding15">
                    <img class="card-img-top col-md-12" src="/image/logo.png" alt="Card image cap">
                    <div class="card-block">
                        <h4 class="card-title">{{env('APP_NAME')}}</h4>
                    </div>
                    <ul class="list-group list-group-flush">

                        <li class="list-group-item"><b>App Name : </b>{{$details['name']}}</li>
                        <li class="list-group-item"><b>App ID : </b>{{$details['id']}}</li>
                        <li class="list-group-item"><b>Full Name : </b>{{$details['full_name']}}</li>
                        <li class="list-group-item"><b>Repo URL : </b>{{$details['html_url']}}</li>
                        <li class="list-group-item"><b>Branch : </b>{{$details['default_branch']}}</li>
                        <li class="list-group-item"><b>Version : </b>{{Setting::get('version.number', "2")}}
                            .{{sizeof($data)}}</li>

                    </ul>
                    <div class="card-block">
                        <a target="_blank" href="{{$details['html_url']}}" class="card-link">Visit Repo</a>
                    </div>
                </div>

            </div>
            <div class="col-md-8">
                <div class="list-group">
                    @foreach($data as $datas)
                        <div class="">
                            <div class="col-md-12">
                                <div class="media card card-block">
                                    <div class="media-left">
                                        <a href="#">
                                            <img class="media-object" width="60" height="60"
                                                 src="{{$datas->author->avatar_url}}"/>
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading">{{$datas->author->login}}</h4>
                                        <label>Hash :</label>{{$datas->sha}}<br/>
                                        <label>Email :</label><a
                                                href="mailto:{{str_replace(">","",$datas->commit->author->email)}}"> {{str_replace(">","",$datas->commit->author->email)}}</a><br/>
                                        <label>Date :</label>{{$datas->commit->author->date}}<br/>
                                        <label>Message :</label>{{$datas->commit->message}}<br/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection