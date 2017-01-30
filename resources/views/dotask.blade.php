@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")
        <div class="col-md-12" style="margin-top: 70px !important;">
            <div class="col-md-8 col-md-offset-2 card card-block padding15">
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
                @if (session()->has('flash_notification.message'))
                    <div class="alert alert-{{ session('flash_notification.level') }}">
                        <button type="button" class="close" data-dismiss="alert"
                                aria-hidden="true">&times;</button>

                        {!! session('flash_notification.message') !!}
                    </div>
                @endif
                <div class="card card-block">
                    <div class="col-md-4 pull-left">

                        <label>Done By :</label> {{Auth::user()->fname}} &nbsp {{Auth::user()->lname}}<br/>
                        <label>Date :</label> {{\Carbon\Carbon::today()->toDateString()}}
                    </div>

                    <div class="col-md-4 pull-right">
                        <label>Artefact Name : </label> {{$artefact->artefact_name}}<br/>
                        <label>Artefact Type :</label> {{$type->artefact_type_long}}

                    </div>
                </div>
                <form method="post" action="/saveConditionalReport">
                    <input type="hidden" name="taskId" value="{{$taskId}}">
                    @foreach($segments as $segment)
                        <fieldset class="fieldset">
                            <legend class="legend1">{{$segment->segment_title}}</legend>
                            <div class="col-md-12">
                                @foreach($segment->report()->get() as $report)
                                    <div class="col-md-6">
                                        <label>{{$report->conditional_report_title}}</label>
                                    </div>


                                    <div class="col-md-6">
                                        @php($default_val = $report->default_value)
                                        @if($report->default_value =='user')
                                            @php($default_val = Auth::user()->fname . " " . Auth::user()->lname)
                                        @elseif($report->default_value=='today')
                                            @php($default_val = \Carbon\Carbon::now()->format('d/m/Y'))
                                        @endif


                                        @if($report->conditional_report_html_type == "text" || $report->conditional_report_html_type == 'tect')
                                            <input title="{{$report->conditional_report_title}}"
                                                   type="text"
                                                   placeholder="{{$report->conditional_report_title}}"
                                                   value="{{$default_val}}"
                                                   class="form-control" name="{{$report->id}}">
                                            <br/>
                                        @elseif($report->conditional_report_html_type == "textarea")
                                            <textarea title="{{$report->conditional_report_title}}"
                                                      placeholder="{{$report->conditional_report_title}}"
                                                      class="form-control"
                                                      rows="5"
                                                      name="{{$report->id}}">{{$default_val}}</textarea><br/>
                                        @elseif($report->conditional_report_html_type == "dropdown")
                                            <select title="{{$report->conditional_report_title}}" class="form-control"
                                                    name="{{$report->id}}">
                                                <option value="0">--select One--</option>
                                                @foreach($report->conditional_report_pick_data as $pick_data)
                                                    @if(strtolower($default_val) == strtolower($pick_data))
                                                        <option selected value="{{$pick_data}}">{{$pick_data}}</option>
                                                    @else
                                                        <option value="{{$pick_data}}">{{$pick_data}}</option>
                                                    @endif
                                                @endforeach
                                            </select><br/>
                                        @elseif($report->conditional_report_html_type == "number")
                                            <input title="{{$report->conditional_report_title}}" type="number"
                                                   placeholder="{{$report->conditional_report_title}}"
                                                   value="{{$default_val}}"
                                                   class="form-control" name="{{$report->id}}"><br/>
                                        @elseif($report->conditional_report_html_type == "date")
                                            <input title="{{$report->conditional_report_title}}" type="text"
                                                   placeholder="{{$report->conditional_report_title}}"
                                                   value="{{$default_val}}"
                                                   class="form-control date" name="{{$report->id}}"><br/>

                                        @elseif($report->conditional_report_html_type == "file")
                                            <div
                                                    id="{{$report->id}}" name="{{$report->id}}" class="dropzone"
                                                    title="{{$report->conditional_report_title}}">
                                                <input type="button" class="btn btn-primary btn-block" id="attUpload"
                                                       value="Upload">
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>
                    @endforeach
                    <br/>
                    <br/>
                    <div class="card card-block">
                        <center>
                            <input type="submit" class="btn btn-success" value="Save">
                            <input type="reset" class="btn btn-danger" value="Reset">
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@include('admin.js')
@section('js')
    <script>
        $(function () {
            $('.date').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy'
            });
            $('.dropzone').dropzone({
                maxFiles: 5,
                paramName: 'file',
                url: "/crreport/import/" + taskID,
                maxFilesize: 10,
                addRemoveLinks: true,
                autoProcessQueue: false,
                acceptedFiles: ".pdf,.png,.jpg,.csv,.xls,.xlsx",
                init: function () {
                    var myDropzone = this;

                    $("#attUpload").click(function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        myDropzone.processQueue();

                    });
                },
                success: function (file, response) {

                }
            });
        })
    </script>
@endsection