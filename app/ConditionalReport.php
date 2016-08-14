<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConditionalReport extends Model
{
    //
    protected $casts = [
        'conditional_report_pick_data' => 'json'
    ];
}
