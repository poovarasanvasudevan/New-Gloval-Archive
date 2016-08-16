<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ConditionalReportsSegment
 *
 * @property integer $id
 * @property integer $artefact_type_id
 * @property string $segment_name
 * @property string $segment_title
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ConditionalReport[] $conditionaReport
 * @method static \Illuminate\Database\Query\Builder|\App\ConditionalReportsSegment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ConditionalReportsSegment whereArtefactTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ConditionalReportsSegment whereSegmentName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ConditionalReportsSegment whereSegmentTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ConditionalReportsSegment whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ConditionalReportsSegment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ConditionalReportsSegment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ConditionalReportsSegment extends Model
{
    //

    public function conditionalReport(){
        return $this->hasMany('App\ConditionalReport');
    }
}
