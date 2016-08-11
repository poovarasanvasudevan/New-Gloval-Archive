<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Role
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $short_name
 * @property string $long_name
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereShortName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereLongName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Role whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Pages[] $pages
 */
class Role extends Model
{
    //
    protected $table = "roles";

    protected $fillable = [
        "short_name",
        "long_name"
    ];

    public function pages()
    {
        return $this->belongsToMany('App\Page');
    }

}
