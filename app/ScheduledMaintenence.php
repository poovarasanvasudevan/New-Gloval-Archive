<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduledMaintenence extends Model
{
    //
    public function scheduledMaintenenceDate(){
        return $this->hasMany('App\ScheduledMaintenenceDate');
    }
}
