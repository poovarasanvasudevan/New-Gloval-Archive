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
                    <div class="location"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('admin.js')
@section('js')
    <script>
        $(function () {
            $(".location").jsGrid({
                height: "85%",
                width: "100%",
                filtering: false,
                editing: true,
                sorting: true,
                inserting: true,
                data: AData,
                controller: {
                    updateItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/updatelocation",
                            data: item
                        });
                    },
                    deleteItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/deletelocation",
                            data: item
                        });
                    },
                    insertItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/insertlocation",
                            data: item
                        });
                    }
                },
                fields: [
                    {name: "id", type: "text", editing: false,inserting:false},
                    {name: "short_name", title: "Short Name", type: "text"},
                    {name: "long_name", title: "Long Name", type: "text"},
                    {name: "is_archive_location", title: "Is Archive Location", type: "checkbox"},
                    {name: "active", type: "checkbox", sorting: false, filtering: false},
                    {type: "control"}
                ]
            });
        })

    </script>
@endsection