<table>
    @foreach($result as $res)
        <tr>
            <td>{{$res->artefact_name}}</td>
        </tr>
    @endforeach
</table>