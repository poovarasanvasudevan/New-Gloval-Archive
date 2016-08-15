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
                            <select class="form-control">
                                <option value="0">--Select--</option>
                                <option value="1">This Week</option>
                                <option value="2">This Month</option>
                                <option value="3">This Year</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr/>

                <div class="col-md-12">
                    @if($result)
                        @foreach($result as $r)
                        <div class="col-md-3">
                            <p>
                                <label>
                                    {{$r->maintenence_date}}
                                </label>
                            </p>
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

@endsection

