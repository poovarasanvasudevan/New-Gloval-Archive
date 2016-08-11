<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Location
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Location archive()
 * @method static \Illuminate\Database\Query\Builder|\App\Location active()
 * @mixin \Eloquent
 * @property integer $id
 * @property string $short_name
 * @property string $long_name
 * @property boolean $is_archive_location
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Location whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Location whereShortName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Location whereLongName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Location whereIsArchiveLocation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Location whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Location whereUpdatedAt($value)
 */
class Location extends Model
{
    //
    protected $table = "locations";

    protected $fillable = [
        'short_name', 'long_name', 'is_archive_location',
    ];

    public function scopeArchive($query)
    {
        return $query->active()->where('is_archive_location', true);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
