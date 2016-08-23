
<div class="col-md-8 col-md-offset-2" >
    <div class="card card-block">

        <div class="row">
            <table width="100%">
                <tr>
                    <td><b><label>Artefact Name</label> </b>&nbsp;&nbsp;{{$artefact->artefact_name}} </td>
                    <td><b> <label>Created Date</label></b>&nbsp;&nbsp;{{$artefact->created_at->toDateString()}}</td>
                </tr>
                <tr>
                    <td><b><label>Created By :</label>&nbsp;</b>&nbsp;{{$artefact->user->fname}} {{$artefact->user->lname}}</td>
                    <td><b><label>Location :</label>&nbsp;</b>&nbsp;{{\App\Location::find($artefact->location)->long_name}}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card card-block">


        @if($attr)
            <fieldset class="fieldset">
                <legend class="legend1">{{$artefact->artefact_name}}</legend>
                <div>
                    <table cellpadding="5px">
                        @foreach($attr as $at)

                            <tr style="margin: 5px !important;">
                                <td style="width: 70% !important;"><b>{{$at->attribute_title}}</b></td>
                                <td style="width: 70% !important;">  {{\App\Helpers\MyHelper::getAttrValues($artefact->id,$at->id)}}  </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </fieldset>
        @else
        @endif
    </div>
</div>