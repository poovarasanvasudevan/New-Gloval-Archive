<?php

namespace App;

use App\Scope\DeveloperScope;
use Auth;
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
 * @property boolean $is_default
 * @property boolean $is_admin_page
 * @method static \Illuminate\Database\Query\Builder|\App\Page whereIsDefault($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Page whereIsAdminPage($value)
 */
class Page extends Model
{
    //
    protected $fillable = [
        "short_name",
        "long_name",
        "url",
        "order",
        'is_admin_page',
        'is_default'
    ];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeveloperScope());
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public static function getUserPage()
    {

        return \DB::table('pages')
            ->select('pages.*')
            ->leftJoin('page_role', 'page_role.page_id', '=', 'pages.id')
            ->leftJoin('roles', 'page_role.role_id', '=', 'roles.id')
            ->leftJoin('users', 'roles.id', '=', 'users.role')
            ->whereRaw('users.id = ?', [Auth::user()->id])
            ->orderBy("pages.id")
            ->get();

    }

}
