<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConditionalReportsSegment extends Model
{
    //

    public function conditionaReport(){
        return $this->hasMany('App\ConditionalReport');
    }
}
