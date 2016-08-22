<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ConditionalReport extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order_scope', function(Builder $builder) {
            $builder->orderBy('sequence_number');
        });
    }
    //
    protected $casts = [
        'conditional_report_pick_data' => 'json'
    ];
}
