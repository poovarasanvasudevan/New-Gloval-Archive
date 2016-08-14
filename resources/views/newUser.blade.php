@extends('layout.layout2')

@section('content')

    <div class="full-bg">
        @include("layout.navbar")


        <div class="col-md-10 col-md-offset-1" style="margin-top: 70px !important;">
            <div class="card card-block">
                <div class="col-md-12">
                    <div id="new-user">
                        <div class="col-md-12">

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
                            <form class="form-horizontal" method="post" id="newUser" action="/userCreate">
                                <div class="form-group">
                                    <label for="fname" class="col-lg-2 control-label">First Name</label>
                                    <div class="col-lg-10">
                                        <input type="text" required class="form-control" name="fname" id="fname"
                                               placeholder="First Name"
                                               value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lname" class="col-lg-2 control-label">Last Name</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="lname" required class="form-control" id="lname"
                                               placeholder="Last Name"
                                               value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="abhayasiId" class="col-lg-2 control-label">Abhyasi Id</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" required id="abhayasiId"
                                               name="abhayasiId"
                                               value=""
                                               placeholder="Abhyasi Id">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-lg-2 control-label">Email</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control" required id="email" name="email"
                                               placeholder="Email"
                                               value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="location" class="col-lg-2 control-label">Location</label>
                                    <div class="col-lg-10">
                                        <select class="form-control" required id="location" name="location">
                                            <option value="0">Select one</option>
                                            @foreach(\App\Location::all() as $location)
                                                <option value="{{$location->id}}">{{$location->long_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="role" class="col-lg-2 control-label">Role</label>
                                    <div class="col-lg-10">
                                        <select class="form-control" required id="role" name="role">
                                            <option value="0">Select one</option>
                                            @foreach(\App\Role::all() as $role)
                                                <option value="{{$role->id}}" selected>{{$role->long_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="role" class="col-lg-2 control-label">Artefacts</label>
                                    <div class="col-lg-10">
                                        <ul>
                                            @foreach(\App\ArtefactType::all() as $artefact)

                                                <div class="checkbox col-md-6">
                                                    <label>
                                                        <input type="checkbox" name="artefact[]" id="{{$artefact->id}}"
                                                               value="{{$artefact->id}}"> {{$artefact->artefact_type_long}}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-lg-10 col-lg-offset-2">
                                        <button type="submit" class="btn btn-primary pull-right">Add User</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection