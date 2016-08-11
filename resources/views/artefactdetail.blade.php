@if($attributes && $artefact)

    <div class="col-md-12">
        <input type="hidden" name="artefactId" value="{{$artefact->id}}">
        @foreach($attributes as $attribute)
            <div class="col-md-3">
                <div class="card card-block">
                    <p class="card-title">{{$attribute->attribute_title}}</p>
                    <div>
                        @if($attribute->html_type =='text')
                            <input type="text" class="form-control" name="{{$attribute->id}}" id="{{$attribute->id}}" value="{{Helper::getAttrValues($artefact->id,$attribute->id)}}">
                        @elseif($attribute->html_type=='number')
                            <input type="number" class="form-control" name="{{$attribute->id}}"  id="{{$attribute->id}}" value="{{Helper::getAttrValues($artefact->id,$attribute->id)}}">
                        @elseif($attribute->html_type=='textarea')
                            <textarea class="form-control" rows="4" cols="5" name="{{$attribute->id}}"  id="{{$attribute->id}}"> {{Helper::getAttrValues($artefact->id,$attribute->id)}}</textarea>
                        @elseif($attribute->html_type=='select')
                            <input type="text" class="form-control autocomplete" name="{{$attribute->id}}"  id="{{$attribute->id}}" value="{{Helper::getAttrValues($artefact->id,$attribute->id)}}">
                        @elseif($attribute->html_type=='date')
                            <input type="text" class="form-control date" name="{{$attribute->id}}"  id="{{$attribute->id}}" value="{{Helper::getAttrValues($artefact->id,$attribute->id)}}">
                        @elseif($attribute->html_type=='file')
                            <input type="file" class="form-control" id="{{$attribute->id}}">
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
