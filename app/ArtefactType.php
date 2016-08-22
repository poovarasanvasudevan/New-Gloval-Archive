<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ConditionalReportsSegment[] $conditionaReportSegment
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
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order_scope', function(Builder $builder) {
            $builder->orderBy('sequence_number');
        });
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function artefact(){
        return $this->hasMany('App\Artefact',"artefact_type");
    }

    public function attributes(){
        return $this->hasMany('App\ArtefactTypeAttribute');
    }

    public function segment(){
        return $this->hasMany('App\ConditionalReportsSegment');
    }
}
