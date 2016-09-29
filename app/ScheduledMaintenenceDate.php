<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

class ScheduledMaintenenceDate extends Model implements HasMedia
{
    use HasMediaTrait;
    //
    protected $table = 'scheduled_maintenence_dates';
    protected $dates = ['maintenence_date'];
    protected $casts = [
        'conditional_report_result_data' => 'json'
    ];

    public function scheduledMaintenence(){
        return $this->belongsTo('App\ScheduledMaintenence','scheduled_maintenence_id');
    }

    public function users(){
        return $this->belongsTo('App\User','user_id');
    }
}
