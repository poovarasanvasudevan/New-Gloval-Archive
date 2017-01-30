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
                    <div class="attrList"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('admin.js')
@section('js')
    <script>
        $(function () {
            $(".attrList").jsGrid({
                height: "90%",
                width: "100%",
                filtering: false,
                editing: true,
                sorting: true,
                inserting: true,
                data: Adata,
                controller: {
                    updateItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/updatepick",
                            data: item
                        });
                    },
                    deleteItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/deletepick",
                            data: item
                        });
                    },
                    insertItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/insertpick",
                            data: item
                        });
                    }
                },
                fields: [
                    {name: "id", type: "text", editing: false},
                    {name: "attribute_id", title: "Attr Id", type: "text", editing: false},
                    {
                        name: "pick_data_value",
                        title: "Value",
                        type: "text"
                    },
                    {name: "active", type: "checkbox", sorting: false, filtering: false},
                    {type: "control"}
                ]
            });
        });

    </script>
@endsection