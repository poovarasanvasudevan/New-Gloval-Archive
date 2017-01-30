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
                <div class="col-md-10 card card-block" style="height: 90% !important;">
                    <div class="users"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('admin.js')
@section('js')
    <script>
        $(function () {
            $(".users").jsGrid({
                height: "90%",
                width: "100%",
                filtering: false,
                editing: true,
                sorting: true,
                inserting: false,
                data: Adata,
                controller: {
                    updateItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/updateuser",
                            data: item
                        });
                    },
                    deleteItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/deletepick",
                            data: item
                        });
                    }
                },
                fields: [
                    {name: "id", type: "text", editing: false},
                    {name: "abhyasiid", title: "Abhyasi Id", type: "text"},
                    {name: "fname", title: "First Name", type: "text"},
                    {name: "lname", title: "Last Name", type: "text"},
                    {name: "email", title: "Email", type: "text"},
                    {name: "password", title: "Password", type: "text"},
                    {name: "is_developer", title: "Admin", type: "checkbox"},
                    {
                        name: "role",
                        title: "Role",
                        type: "select",
                        items: role,
                        valueField: "id",
                        textField: "long_name"
                    },
                    {
                        name: "location",
                        title: "Location",
                        type: "select",
                        items: loc,
                        valueField: "id",
                        textField: "long_name"
                    },
                    {name: "active", type: "checkbox", sorting: false, filtering: false},
                    {type: "control"}
                ]
            });
        });
    </script>
@endsection