<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

/**
 * App\Artefact
 *
 * @property integer $id
 * @property string $old_artefact_id
 * @property integer $location
 * @property integer $artefact_type
 * @property string $artefact_values
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Artefact whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Artefact whereOldArtefactId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Artefact whereLocation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Artefact whereArtefactType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Artefact whereArtefactValues($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Artefact whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Artefact whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Artefact whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\ArtefactType $artefactType
 * @property-read \App\User $userId
 * @property-read \App\Artefact $parentId
 */
class Artefact extends Model implements HasMedia
{
    use HasMediaTrait;
    //
    protected $fillable = [
        "old_artefact_id",
        "location",
        "parent_id",
        "artefact_name",
        "artefact_type",
        "artefact_values",
        "user_id",
        "active"
    ];

    protected $casts = [
        'artefact_values' => 'json'
    ];

    public function artefacttype()
    {
        return $this->belongsTo('App\ArtefactType','artefact_type');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function parent()
    {
        return $this->belongsTo('App\Artefact',"parent_id");
    }

    public function location()
    {
        return $this->belongsTo('App\Location');
    }

    public function getKids($id)
    {
        $count = Artefact::whereParentId($id)->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function cico(){
        return $this->hasMany('App\Cico');
    }

    public function scheduledMaintenence(){
        return $this->hasMany('App\ScheduledMaintenence');
    }
}
