@extends('layout.layout2')
@section('content')
    <div class="full-div">
        @include('layout.adminnav')
        <div style="margin-top: 70px !important;">
            @if (session()->has('flash_notification.message'))
                <div class="alert alert-{{ session('flash_notification.level') }}">
                    <button type="button" class="close" data-dismiss="alert"
                            aria-hidden="true">&times;</button>

                    {!! session('flash_notification.message') !!}
                </div>
            @endif
            <div class="col-md-12">
                <div class="col-md-2">
                    @include('admin.sidebar')
                </div>
                <div class="col-md-10 card card-block" style="height: 90% !important;overflow-y: auto">
                    @foreach($gits as $git)
                        <div class="">
                            <div class="col-md-12">
                                <div class="media card card-block">
                                    <div class="media-left">
                                        <a href="#">
                                            <img class="media-object"  src="{{Gravatar::src($git['email'], 50)}}" />
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading">{{$git['author']}}</h4>
                                        <label>Hash :</label>{{$git['hash']}}<br/>
                                        <label>Email :</label><a href="mailto:{{str_replace(">","",$git['email'])}}"> {{str_replace(">","",$git['email'])}}</a><br/>
                                        <label>Date :</label>{{$git['date']}}<br/>
                                        <label>Message :</label>{{$git['message']}}<br/>
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
    <script>

    </script>
@endsection