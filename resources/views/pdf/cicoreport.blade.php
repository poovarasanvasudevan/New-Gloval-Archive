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
    <div>
        <table cellpadding="5" border="1">
            <thead>
            <tr bgcolor="#4FC3F7">
                <td><FONT COLOR="#fff">Artefact Name</FONT></td>
                <td><FONT COLOR="#fff">CheckOut Time</FONT></td>
                <td><FONT COLOR="#fff">Checkout Description</FONT></td>
                <td><FONT COLOR="#fff">CheckIn Time</FONT></td>
                <td><FONT COLOR="#fff">CheckIn Description</FONT></td>
                <td><FONT COLOR="#fff">Remarks</FONT></td>
                <td><FONT COLOR="#fff">Is Checked In</FONT></td>
                <td><FONT COLOR="#fff">Done By</FONT></td>
            </tr>
            </thead>
            <tbody>
            @foreach($datas as $data)
                <tr>
                    <td>{{$data->artefact->artefact_name}}</td>
                    <td>{{$data->created_at}}</td>
                    <td>{{$data->check_out_description}}</td>
                    <td>{{$data->updated_at}}</td>
                    @if($data->check_in_description)
                        <td>{{$data->check_in_description}}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{$data->remarks}}</td>
                    @if($data->check_out_status)
                        <td>No</td>
                    @else
                        <td>Yes</td>
                    @endif
                    <td>{{$data->user->fname}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>
</body>
</html>