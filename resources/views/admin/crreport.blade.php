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
                    <div class="col-md-12">
                        <div class="card card-block">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="fname" class="col-lg-2 control-label">Artefact Types</label>
                                        <div class="col-lg-10">
                                            @if($artefact_types)
                                                <select class="form-control" id="artefactTypes">
                                                    <option value="0">select Artefact</option>
                                                    @foreach($artefact_types as $artefacttype)
                                                        <option value="{{$artefacttype->id}}">{{$artefacttype->artefact_type_long}}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <input type="button" class="btn btn-success" id="addSection" value="Add Section">
                                </div>
                                <div class="col-md-5">
                                    <div class="alert alert-warning">
                                        <button type="button" class="close" data-dismiss="alert"
                                                aria-hidden="true">&times;</button>
                                        <p>Deleting Data is Not Recoverable,So Please be Careful</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card card-block">
                            <div class="segment_table"></div>
                            <div class="value_table"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('admin.js')
@section('js')
    <script>
        $(function () {
            var PickField = function(config) {
                jsGrid.Field.call(this, config);
            };

            PickField.prototype = new jsGrid.Field({

                itemTemplate: function(value) {
                    if (value != null) {
                        var txt = "";
                        for (i = 0; i < value.length; i++) {
                            txt += value[i] + "@" + "\n";
                        }
                        return "<textarea class='form-control' rows='6' cols='5'>"+txt+"</textarea>";
                    } else {
                        return "<textarea class='form-control'></textarea>";
                    }
                },

                insertTemplate: function(value) {
                    if (value != null) {
                        var txt = "";
                        for (i = 0; i < value.length; i++) {
                            txt += value[i] + "@" + "\n";
                        }
                        return "<textarea class='form-control' rows='6' cols='5'>"+txt+"</textarea>";
                    } else {
                        return "<textarea class='form-control'></textarea>";
                    }
                },

                editTemplate: function(value) {
                    if (value != null) {
                        var txt = "";
                        for (i = 0; i < value.length; i++) {
                            txt += value[i] + "@" + "\n";
                        }
                        return "<textarea class='form-control' rows='6' cols='5'>"+txt+"</textarea>";
                    } else {
                        return "<textarea class='form-control'></textarea>";
                    }
                }
            });

            jsGrid.fields.myDateField = PickField;

            $('#artefactTypes').val(atype)
            $('#addSection').hide();
            var htmlTypes = [
                {
                    id: "textarea",
                    value: "textarea"
                },
                {
                    id: "dropdown",
                    value: "dropdown"
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
            if (atype != 0 || atype != '0') {
                $('#addSection').show();
                $('.value_table').hide();
                $(".segment_table").show();

                $(".segment_table").jsGrid({
                    height: "85%",
                    width: "100%",
                    filtering: false,
                    editing: true,
                    sorting: true,
                    inserting: true,
                    data: segment,
                    controller: {
                        updateItem: function (item) {
                            return $.ajax({
                                type: "POST",
                                url: "/admin/updatesegment",
                                data: item
                            });
                        },
                        deleteItem: function (item) {
                            return $.ajax({
                                type: "POST",
                                url: "/admin/deletesegment",
                                data: item
                            });
                        },
                        insertItem: function (item) {
                            return $.ajax({
                                type: "POST",
                                url: "/admin/insertsegment/" + atype,
                                data: item
                            });
                        }
                    },
                    fields: [
                        {name: "id", type: "text", editing: false,inserting:false},
                        {name: "segment_name", title: "Name", type: "textarea"},
                        {name: "segment_title", title: "Display Title", type: "text"},
                        {name: "sequence_number", title: "Sequence", type: "number"},
                        {name: "active", type: "checkbox", sorting: false, filtering: false},
                        {
                            name: "id", title: "View", type: "button", width: 50, align: "center",editing: false,inserting:false,
                            itemTemplate: function (value) {
                                return "<a href='"+value+"' class='btn btn-success'>View</a>";
                            }
                        },
                        {type: "control"}
                    ]
                });
            }

            if (sectionData != 0 || sectionData != '0') {
                console.log(sectionData)
                $('.value_table').show();
                $(".segment_table").hide();

                $(".value_table").jsGrid({
                    height: "85%",
                    width: "100%",
                    filtering: false,
                    editing: true,
                    sorting: true,
                    inserting: true,
                    data: sectionData,
                    controller: {
                        updateItem: function (item) {
                            return $.ajax({
                                type: "POST",
                                url: "/admin/updatesegmentvalue",
                                data: item
                            });
                        },
                        deleteItem: function (item) {
                            return $.ajax({
                                type: "POST",
                                url: "/admin/deletesegmentvalue",
                                data: item
                            });
                        },
                        insertItem: function (item) {
                            return $.ajax({
                                type: "POST",
                                url: "/admin/insertsegmentvalue/" + section,
                                data: item
                            });
                        }
                    },
                    fields: [
                        {name: "id", type: "text", editing: false, width: "30",inserting:false},
                        {name: "conditional_report_title", title: "Display Title", type: "textarea"},
                        {
                            name: "conditional_report_html_type",
                            title: "Type",
                            type: "select",
                            items: htmlTypes,
                            valueField: "id",
                            textField: "value",
                            width: "70"
                        },
                        {
                            name: "conditional_report_pick_data",
                            type: "textarea",
                            title: "PickData",
                            sorting: false,
                            filtering: false,
                            itemTemplate: function (value) {
                                if (value != null) {
                                    var txt = "";
                                    for (i = 0; i < value.length; i++) {
                                        txt += value[i] + "@" + "\n";
                                    }
                                    return "<textarea class='form-control' rows='6' cols='5'>"+txt+"</textarea>";
                                } else {
                                    return "<textarea class='form-control'></textarea>";
                                }
                            }
                        },
                        {name: "default_value", title: "Default", type: "text", width: "50"},
                        {name: "sequence_number", title: "Sequence", type: "number", width: "50"},
                        {name: "active", title: "Active", type: "checkbox", sorting: false, filtering: false},
                        {type: "control"}
                    ]
                });
            }

            $('#artefactTypes').change(function () {
                window.location = "/admin/crreport/" + $(this).val() + "/0";
            })

            $('#segment').change(function () {
                window.location = "/admin/crreport/" + $('#artefactTypes').val() + "/" + $(this).val();
            })
        })

    </script>
@endsection