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

                    <div class="card card-block">
                        <div class="form-group col-md-12">
                            <label for="artefactTypes">Artefact Types</label>
                            <select class="form-control" name="artefactTypes" id="artefactTypes">
                                <option value="0">Select One</option>
                                @foreach($ats as $at)
                                    <option value="{{$at->id}}">{{$at->artefact_type_long}}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    <div id="excelimports" class="dropzone"></div>

                    <br/>
                    <div class="card card-block">
                        <div class="pull-left"><label id="result"></label></div>
                        <button class="btn btn-success pull-right" id="import">Import</button>
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
            $('#excelimports').dropzone({
                url: "/admin/importartefact",
                maxFilesize: 4,
                addRemoveLinks: true,
                autoProcessQueue: false,
                acceptedFiles: ".csv",
                init: function () {
                    var myDropzone = this;

                    $("#import").click(function (e) {
                        e.preventDefault();

                        if ($('#artefactTypes').val() != 0) {
                            e.stopPropagation();
                            myDropzone.processQueue();
                        } else {
                            alert('Select the artefact type')
                        }
                    });
                },
                sending: function (file, xhr, formData) {
                    formData.append('artefacttype', $('#artefactTypes').val());
                },
                success: function (file, response) {
                    $('#result').html(response.command);
                }
            });
        })
    </script>
@endsection