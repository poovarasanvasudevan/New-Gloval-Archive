<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <style>
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
<div class="col-md-12">
    <div class="row">
        <table width="100%">
            <tr>
                <td><b><label>Done By</label> </b>&nbsp;&nbsp;{{$user}}</td>
                <td><b> <label>Date</label></b>&nbsp;&nbsp;{{$segments->first()->report()->get()->first()->updated_at}}
                </td>
            </tr>
            <tr>
                <td><b><label>Artefact Name :</label>&nbsp;</b>&nbsp;{{$artefact_name}}</td>
                <td><b><label>Artefact Type :</label>&nbsp;</b>&nbsp;{{$type}}</td>
            </tr>
        </table>
    </div
    <br/>

    <hr/>

    <div>
        @php($i=0)
        @foreach($segments as $segment)
            <fieldset>
                <legend>{{$segment->segment_title}}</legend>
                <div class="col-md-12">
                    <table cellpadding="5px">
                        @foreach($segment->report()->get() as $report)

                            <tr style="margin: 5px !important;">
                                <td style="width: 70% !important;"><b>{{$report->conditional_report_title}}</b></td>
                                <td style="width: 70% !important;">

                                    @foreach($report2->conditional_report_result_data as $resultData)
                                        @if($resultData['cr_id'] == $report->id)
                                            @if($report->conditional_report_html_type=='dropdown' && $resultData['cr_value']=='0')

                                            @elseif($report->conditional_report_html_type=='file')
                                                @if($report->hasMedia())
                                                    @foreach($report->getMedia() as $media)
                                                        <b><a href="{{$media->getUrl()}}">Download</a> </b><br/>
                                                    @endforeach
                                                @endif
                                                jkghku
                                            @else
                                                <center> {{$resultData['cr_value']}}</center>
                                            @endif
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </fieldset>
            <br/>
            @if($i==2)
                @php($i=0)
                <div class="page-break"></div>
            @endif
            @php($i++)
        @endforeach
    </div>
</div>
</body>
</html>