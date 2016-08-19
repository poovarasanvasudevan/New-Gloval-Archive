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
                <div class="col-md-10 card card-block" style="height: 92% !important;">
                    <div class="tables"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            $(".tables").jsGrid({
                height: "auto",
                width: "100%",
                autoload: true,
                filtering: false,
                editing: true,
                sorting: true,
                paging: true,
                inserting:true,
                pageSize: 10,
                pageButtonCount: 5,
                controller: {
                    loadData: function (filter) {
                        return $.ajax({
                            type: "GET",
                            url: "/admin/getAllArtefactTypes",
                            data: filter
                        });
                    },
                    updateItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/updateArtefactTypes",
                            data: item
                        });
                    },
                    deleteItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/deleteArtefactTypes",
                            data: item
                        });
                    },
                    insertItem:function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/addArtefactTypes",
                            data: item
                        });
                    }
                },

                fields: [
                    {name: "id", type: "text"},
                    {name: "artefact_type_short",title:"Short Name", type: "text"},
                    {name: "artefact_type_long",title:"Long Name", type: "text"},
                    {name: "artefact_description",title:"Description", type: "text"},
                    {name: "active", type: "checkbox", sorting: false, filtering: false},
                    {type: "control"}
                ]
            });
        })
    </script>
@endsection