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
                   <div class="pageList"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('admin.js')
@section('js')
    <script>
        $(function () {
            $(".pageList").jsGrid({
                height: "90%",
                width: "100%",
                filtering: false,
                editing: true,
                sorting: true,
                inserting: true,
                data: datas,
                controller: {
                    updateItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/updatePage",
                            data: item
                        });
                    },
                    insertItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/addPage",
                            data: item
                        });
                    }
                },
                fields: [
                    {name: "id", type: "text", editing: false},
                    {name: "short_name", title: "Short Name", type: "text"},
                    {name: "long_name", title: "Long Name", type: "text"},
                    {name: "url", title: "URL", type: "text"},
                    {name: "sequence_number", title: "Sequence", type: "text"},
                    {name: "is_default", title: "Default", type: "checkbox"},
                    {name: "active",title:"Active", type: "checkbox", sorting: false, filtering: false},
                    {type: "control"}
                ]
            });
        })
    </script>
@endsection