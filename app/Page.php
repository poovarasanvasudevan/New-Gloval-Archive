<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Pages
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $short_name
 * @property string $long_name
 * @property string $url
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Pages whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pages whereShortName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pages whereLongName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pages whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pages whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pages whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pages whereUpdatedAt($value)
 * @property integer $order
 * @method static \Illuminate\Database\Query\Builder|\App\Pages whereOrder($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 */
class Page extends Model
{
    //
    protected $fillable = [
        "short_name",
        "long_name",
        "url",
        "order"
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }
}
