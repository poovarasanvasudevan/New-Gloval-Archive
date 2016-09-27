@if($attributes && $artefact)

    <div class="col-md-12">
        <input type="hidden" name="artefactId" value="{{$artefact->id}}">
        @if($artefact->hasMedia())
            <div class="card carousel row padding15" id="mediaCard">
                @foreach($artefact->getMedia() as $media)



                    <div class="col-md-1">
                        @if($media->getExtensionAttribute() == 'xlsx' || $media->getExtensionAttribute() == 'xls' || $media->getExtensionAttribute() == '.csv')

                            <div class="card cc{{$media->id}}">
                                <img class="card-img-top img-responsive" src="/assets/images/Excel-icon.png" alt="Card image cap">
                                <div class="card-block">
                                    <a href="{{$media->getUrl()}}" target="_blank" class="btn btn-success">
                                        <i class="fa fa-file-excel-o"></i>
                                    </a>
                                    <a  id="{{$media->id}}" class="btn btn-danger delete">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </div>
                            </div>
                        @elseif($media->getExtensionAttribute() == 'pdf')

                            <div class="card cc{{$media->id}}">
                                <img class="card-img-top img-responsive" src="/assets/images/pdf-icon-1.png" alt="Card image cap">
                                <div class="card-block">
                                    <a href="{{$media->getUrl()}}" target="_blank" class="btn btn-success">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                    <a  id="{{$media->id}}"  class="btn btn-danger delete">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </div>
                            </div>

                        @else
                            <div class="card cc{{$media->id}}">

                                <img class="card-img-top img-responsive" src="/assets/images/Docs-icon.png" alt="Card image cap">
                                <div class="card-block">
                                    <a href="{{$media->getUrl()}}" target="_blank" class="btn img-media btn-success">
                                        <i class="fa fa-file-image-o"></i>
                                    </a>
                                    <a  id="{{$media->id}}"  class="btn btn-danger delete">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </div>
                            </div>

                        @endif
                    </div>
                @endforeach
            </div>
        @endif
        @foreach($attributes as $attribute)
            <div class="col-md-3">
                <div class="card card-block">
                    <p class="card-title">{{$attribute->attribute_title}}</p>
                    <div>
                        @if($attribute->html_type =='text')
                            <input type="text" title="{{$attribute->attribute_title}}" class="form-control"
                                   name="{{$attribute->id}}" id="{{$attribute->id}}"
                                   value="{{Helper::getAttrValues($artefact->id,$attribute->id)}}">
                        @elseif($attribute->html_type=='number')
                            <input type="number" title="{{$attribute->attribute_title}}" class="form-control"
                                   name="{{$attribute->id}}" id="{{$attribute->id}}"
                                   value="{{Helper::getAttrValues($artefact->id,$attribute->id)}}">
                        @elseif($attribute->html_type=='textarea')
                            <textarea class="form-control" title="{{$attribute->attribute_title}}" rows="4" cols="5"
                                      name="{{$attribute->id}}"
                                      id="{{$attribute->id}}"> {{Helper::getAttrValues($artefact->id,$attribute->id)}}</textarea>
                        @elseif($attribute->html_type=='select')
                            <input type="text" title="{{$attribute->attribute_title}}" class="form-control autocomplete"
                                   name="{{$attribute->id}}" id="{{$attribute->id}}"
                                   value="{{Helper::getAttrValues($artefact->id,$attribute->id)}}">
                        @elseif($attribute->html_type=='date')
                            <input type="text" title="{{$attribute->attribute_title}}" class="form-control date"
                                   name="{{$attribute->id}}" id="{{$attribute->id}}"
                                   value="{{Helper::getAttrValues($artefact->id,$attribute->id)}}">
                        @elseif($attribute->html_type=='file')
                            <div id="excelimports" id="{{$attribute->id}}" name="{{$attribute->id}}" class="dropzone"
                                 title="{{$attribute->attribute_title}}">

                                <input type="button" class="btn btn-primary btn-block" id="attUpload" value="Upload">
                            </div>
                        @elseif($attribute->html_type=='dropdown')
                            <select name="{{$attribute->id}}" title="{{$attribute->attribute_title}}"
                                    id="{{$attribute->id}}" class="form-control">
                                <option value="">select one</option>
                                @foreach($attribute->select_pick_data as $pick)
                                    <option value="{{$pick}}">{{$pick}}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
