<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ArtefactTypeAttribute
 *
 * @property integer $id
 * @property integer $artefact_type
 * @property string $html_type
 * @property boolean $is_searchable
 * @property boolean $pick_flag
 * @property integer $pick_data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PickData[] $pickData
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactTypeAttribute whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactTypeAttribute whereArtefactType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactTypeAttribute whereHtmlType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactTypeAttribute whereIsSearchable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactTypeAttribute wherePickFlag($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactTypeAttribute wherePickData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactTypeAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactTypeAttribute whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property boolean $active
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactTypeAttribute whereActive($value)
 * @property string $attribute_title
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactTypeAttribute whereAttributeTitle($value)
 * @property integer $artefact_type_id
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactTypeAttribute whereArtefactTypeId($value)
 */
class ArtefactTypeAttribute extends Model
{
    //
    public function __construct()
    {
     //   parent::__construct($attributes);

    }

    public function pickData()
    {
        return $this->hasMany('App\PickData');
    }
}
