<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PickData
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property string $pick_data_value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\ArtefactTypeAttribute $artefactTypeAttribute
 * @method static \Illuminate\Database\Query\Builder|\App\PickData whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PickData whereAttributeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PickData wherePickDataValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PickData whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PickData whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property boolean $active
 * @method static \Illuminate\Database\Query\Builder|\App\PickData whereActive($value)
 */
class PickData extends Model
{
    //
    protected $table="pick_data";


    public function artefactTypeAttribute() {
        return $this->belongsTo('App\ArtefactTypeAttribute');
    }
}
