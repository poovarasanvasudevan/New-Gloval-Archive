@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")

        <div class="col-md-8 card card-block col-md-offset-2" style="margin-top: 70px !important;">

            @if (session()->has('flash_notification.message'))
                <div class="alert alert-{{ session('flash_notification.level') }}">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                    {!! session('flash_notification.message') !!}
                </div>
            @endif

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


            <div class="panel panel-body">
                <a id="roleOpner" href="#newRole" class="btn btn-success pull-right">Add New Role</a>
            </div>

            <div>
                <div id="roleGrid" class="table"></div>
                <div id="pager"></div>

            </div>

            <form id="newRole" class="white-popup-block mfp-hide" method="post" action="/addRole">
                <fieldset>
                    <legend>New Role</legend>
                    <div class="form-group">
                        <label for="fname" class="col-lg-2 control-label">Role Name</label>
                        <div class="col-lg-10">
                            <input type="text" required class="form-control" name="rolename" id="fname"
                                   placeholder="Role Name"
                                   value="">
                        </div>
                    </div>
                    <br/>
                    <br/>
                    <br/>
                    <div class="form-group">
                        <label for="fname" class="col-lg-2 control-label">Pages</label>
                        <div class="col-lg-10">
                            @foreach($pages as $page)
                                <div class="checkbox col-md-6">
                                    <label>
                                        <input type="checkbox" name="pages[]" id="{{$page->id}}"
                                               value="{{$page->id}}"> {{$page->long_name}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <hr/>
                    <div>
                        <input type="submit" value="Create" class="btn btn-success pull-right">
                    </div>
                </fieldset>
            </form>

        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {

            $('a#roleOpner').magnificPopup({
                type: 'inline',
                midClick: true // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
            });


            $("#roleGrid").jsGrid({
                height: "auto",
                width: "100%",
                autoload: true,
                filtering: true,
                editing: true,
                sorting: true,
                paging: true,
                pageSize: 10,
                pageButtonCount: 5,
                controller: {
                    loadData: function (filter) {
                        return $.ajax({
                            type: "GET",
                            url: "/allroles",
                            data: filter
                        });
                    },
                    updateItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/updateRole",
                            data: item
                        });
                    },
                    deleteItem: function (item) {
                        swal("Error", "Your don't have developer Permission :)", "error");
                        return $.ajax({
                            type: "POST",
                            url: "/updateRole",
                            data: item
                        });
                    }
                },

                fields: [
                    {name: "id", type: "text"},
                    {name: "short_name", type: "text"},
                    {name: "long_name", type: "text"},
                    {name: "active", type: "checkbox", sorting: false, filtering: false},
                    {type: "control"}
                ]
            });

        });
    </script>
@endsection