<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduledMaintenenceDate extends Model
{
    //
    protected $table = 'scheduled_maintenence_dates';
    protected $dates = ['maintenence_date'];
    protected $casts = [
        'conditional_report_pick_data' => 'json'
    ];



}
