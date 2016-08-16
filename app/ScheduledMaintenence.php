<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * App\ScheduledMaintenence
 *
 * @property integer $id
 * @property integer $artefact_id
 * @property string $maintenence_type
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ScheduledMaintenenceDate[] $scheduledMaintenenceDate
 * @property-read \App\Artefact $artefactId
 * @method static \Illuminate\Database\Query\Builder|\App\ScheduledMaintenence whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ScheduledMaintenence whereArtefactId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ScheduledMaintenence whereMaintenenceType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ScheduledMaintenence whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ScheduledMaintenence whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ScheduledMaintenence whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ScheduledMaintenence extends Model
{
    //
    protected $fillable = [
        "artefact_id",
        "maintenence_type",
        "active"
    ];

    public function scheduledMaintenenceDate()
    {
        return $this->hasMany('App\ScheduledMaintenenceDate');
    }

    public function artefactId()
    {
        return $this->belongsTo('App\Artefact', 'artefact_id');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-m-Y');
    }

}
