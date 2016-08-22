@extends('layout.layout2')
@section('content')
    <div class="full-div">
        @include('layout.adminnav')
        <div style="margin-top: 70px !important;">
            <div class="col-md-12">
                <div class="col-md-2">
                    @include('admin.sidebar')
                </div>
                <div class="col-md-10 card card-block" style="height: 92% !important;">
                    @if (session()->has('flash_notification.message'))
                        <div class="alert alert-{{ session('flash_notification.level') }}">
                            <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>

                            {!! session('flash_notification.message') !!}
                        </div>
                    @endif
                    <div class="card card-block">
                        <div class="form-group">
                            <label for="artefactTypes">Artefact Types</label>
                            <select class="form-control" name="artefactTypes" id="artefactTypes">
                                <option value="0">Select One</option>
                                @foreach($ats as $at)
                                    <option value="{{$at->id}}">{{$at->artefact_type_long}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="tables"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include ('admin.js')
@section('js')
    <script>
        $(function () {
            $('#artefactTypes').val(AType);
            $('#artefactTypes').change(function () {
                window.location = '/admin/attributes/' + $(this).val()
            })
            var htmlTypes = [
                {
                    id: "textarea",
                    value: "textarea"
                },
                {
                    id: "select",
                    value: "select"
                }, {
                    id: "date",
                    value: "date"
                },
                {
                    id: "text",
                    value: "text"
                },
                {
                    id: "number",
                    value: "number"
                }
            ];
            var allData;

            $.ajax({
                type: "GET",
                url: "/admin/getAllAttributes/" + $(this).val(),
                success: function (data) {
                    allData = data;
                }
            });
            $(".tables").jsGrid({
                height: "79%",
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
                            url: "/admin/updateAttributes",
                            data: item,
                            success:function (data) {
                                console.log(data);
                            }
                        });
                    },
                    deleteItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/deleteAttributes",
                            data: item
                        });
                    },
                    insertItem: function (item) {
                        return $.ajax({
                            type: "POST",
                            url: "/admin/addAttributes/" + AType,
                            data: item
                        });
                    }
                },
                fields: [
                    {name: "id", type: "text", editing: false,inserting:false},
                    {name: "attribute_title", title: "Title", type: "text"},
                    {
                        name: "html_type",
                        title: "Data type",
                        type: "select",
                        items: htmlTypes,
                        valueField: "id",
                        textField: "value"
                    },
                    {name: "pick_flag", title: "Pick Type", type: "checkbox", sorting: false, filtering: false},
                    {
                        name: "is_searchable",
                        title: "Is searchable",
                        type: "checkbox",
                        sorting: false,
                        filtering: false
                    },
                    {name: "sequence_number", title: "Sequence", type: "text"},
                    {name: "active", type: "checkbox", sorting: false, filtering: false},
                    {type: "control"}
                ]
            });

        })
    </script>
@endsection