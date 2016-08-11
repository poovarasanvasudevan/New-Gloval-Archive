@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")
        <div class="col-md-4 col-md-offset-4 padding40 card marginT10 card-block" style="margin-top: 5% !important;">
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
            <form class="form-horizontal" method="post" action="/userEdit/{{$user->id}}/update">
                <div class="form-group">
                    <label for="fname" class="col-lg-2 control-label">First Name</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" name="fname" id="fname" placeholder="First Name"
                               value="{{$user->fname}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lname" class="col-lg-2 control-label">Last Name</label>
                    <div class="col-lg-10">
                        <input type="text" name="lname" class="form-control" id="lname" placeholder="Last Name"
                               value="{{$user->lname}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="abhayasiId" class="col-lg-2 control-label">Abhyasi Id</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="abhayasiId" name="abhayasiId"
                               value="{{$user->abhyasiid}}"
                               placeholder="Abhyasi Id">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="col-lg-2 control-label">Email</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="email" name="email" placeholder="Email"
                               value="{{$user->email}}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="location" class="col-lg-2 control-label">Location</label>
                    <div class="col-lg-10">
                        <select class="form-control" id="location" name="location">
                            <option value="0">Select one</option>
                            @foreach($locations as $location)
                                @if($location->id == $user->location)
                                    <option value="{{$location->id}}" selected>{{$location->long_name}}</option>
                                @else
                                    <option value="{{$location->id}}">{{$location->long_name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="role" class="col-lg-2 control-label">Role</label>
                    <div class="col-lg-10">
                        <select class="form-control" id="role" name="role">
                            <option value="0">Select one</option>
                            @foreach($roles as $role)
                                @if($role->id == $user->role)
                                    <option value="{{$role->id}}" selected>{{$role->long_name}}</option>
                                @else
                                    <option value="{{$role->id}}">{{$role->long_name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="role" class="col-lg-2 control-label">Role</label>
                    <div class="col-lg-10">
                        <ul>
                            @foreach($artefacts as $artefact)
                                @php($done = false)
                                <div class="checkbox">

                                    @foreach($availables as $available)
                                        @if($available->id == $artefact->id)
                                            <label>
                                                <input checked type="checkbox" name="artefact[]" id="{{$artefact->id}}"
                                                       value="{{$artefact->id}}"> {{$artefact->artefact_type_long}}
                                            </label>
                                            @php($done = true)
                                        @endif
                                    @endforeach


                                    @if($done == false)
                                        <label>
                                            <input type="checkbox" name="artefact[]" id="{{$artefact->id}}"
                                                   value="{{$artefact->id}}"> {{$artefact->artefact_type_long}}
                                        </label>
                                    @endif
                                </div>
                            @endforeach
                        </ul>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                        <button type="submit" class="btn btn-primary pull-right">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection