<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ArtefactType
 *
 * @property integer $id
 * @property string $artefact_type_short
 * @property string $artefact_type_long
 * @property string $artefact_description
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereArtefactTypeShort($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereArtefactTypeLong($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereArtefactDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ArtefactTypeAttribute[] $attributes
 */
class ArtefactType extends Model
{
    //
    protected $table="artefact_types";

    protected $fillable = [
      'artefact_type_short' ,
      'artefact_type_long' ,
      'artefact_description' ,
      'active' ,
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function attributes(){
        return $this->hasMany('App\ArtefactTypeAttribute');
    }
}
