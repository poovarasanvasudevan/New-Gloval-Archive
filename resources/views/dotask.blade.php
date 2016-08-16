@extends('layout.layout2')

@section('content')
    <div class="full-bg">
        @include("layout.navbar")
        <div class="col-md-12" style="margin-top: 70px !important;">
            <div class="col-md-8 col-md-offset-2 card card-block padding15">
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
                        <fieldset>
                            <legend>{{$segment->segment_title}}</legend>
                            <div class="col-md-12">
                                @foreach($segment->conditionalReport()->get() as $report)
                                    <div class="col-md-6">
                                        <label>{{$report->conditional_report_title}}</label>
                                    </div>


                                    <div class="col-md-6">
                                        @if($report->conditional_report_html_type == "text" || $report->conditional_report_html_type == 'tect')
                                            <input title="{{$report->conditional_report_title}}"
                                                   type="text"
                                                   placeholder="{{$report->conditional_report_title}}"
                                                   class="form-control" name="{{$report->id}}">
                                            <br/>
                                        @elseif($report->conditional_report_html_type == "textarea")
                                            <textarea title="{{$report->conditional_report_title}}"
                                                      placeholder="{{$report->conditional_report_title}}"
                                                      class="form-control"
                                                      rows="5" name="{{$report->id}}"></textarea><br/>
                                        @elseif($report->conditional_report_html_type == "dropdown")
                                            <select title="{{$report->conditional_report_title}}" class="form-control" name="{{$report->id}}">
                                                <option value="0">--select One--</option>
                                                @foreach($report->conditional_report_pick_data as $pick_data)
                                                    <option value="{{$pick_data}}">{{$pick_data}}</option>
                                                @endforeach
                                            </select><br/>
                                        @elseif($report->conditional_report_html_type == "number")
                                            <input title="{{$report->conditional_report_title}}" type="number"
                                                   placeholder="{{$report->conditional_report_title}}"
                                                   class="form-control" name="{{$report->id}}"><br/>
                                        @elseif($report->conditional_report_html_type == "date")
                                            <input title="{{$report->conditional_report_title}}" type="text"
                                                   placeholder="{{$report->conditional_report_title}}"
                                                   class="form-control date" name="{{$report->id}}"><br/>
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

@section('js')
    <script>
        $(function () {
            $('.date').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy'
            });
        })
    </script>
@endsection