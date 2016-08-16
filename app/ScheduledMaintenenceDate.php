<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ScheduledMaintenenceDate extends Model
{
    //
    protected $table = 'scheduled_maintenence_dates';
    protected $dates = ['maintenence_date'];
    protected $casts = [
        'conditional_report_pick_data' => 'json'
    ];
    public function getMaintenenceDateAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('d-m-Y');
    }

    public function scheduledMaintenence(){
        return $this->belongsTo('App\ScheduledMaintenence','scheduled_maintenence_id');
    }
}
