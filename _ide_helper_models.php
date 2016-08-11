<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\ArtefactType
 *
 * @property integer $id
 * @property string $artefact_type_short
 * @property string $artefact_type_long
 * @property string $artefact_description
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereArtefactTypeShort($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereArtefactTypeLong($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereArtefactDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ArtefactType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ArtefactType extends \Eloquent {}
}

namespace App{
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
	class Location extends \Eloquent {}
}

namespace App{
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
	class Role extends \Eloquent {}
}

namespace App{
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
	class Page extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $abhyasiid
 * @property string $fname
 * @property string $lname
 * @property string $email
 * @property string $password
 * @property integer $role
 * @property integer $location
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereAbhyasiid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereFname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLocation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

