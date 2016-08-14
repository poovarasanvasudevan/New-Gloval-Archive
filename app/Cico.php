<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Cico
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $artefact_id
 * @property integer $user_id
 * @property boolean $check_out_status
 * @property string $check_out_description
 * @property string $check_in_description
 * @property string $remarks
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Cico whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cico whereArtefactId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cico whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cico whereCheckOutStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cico whereCheckOutDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cico whereCheckInDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cico whereRemarks($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cico whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Cico whereUpdatedAt($value)
 */
class Cico extends Model
{
    //
    protected $table = 'cico';
}
