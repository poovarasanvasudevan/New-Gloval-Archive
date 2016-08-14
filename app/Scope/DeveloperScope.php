<?php
namespace App\Scope;

use Illuminate\Database\Eloquent\Scope;

/**
 * Created by PhpStorm.
 * User: Poovarasan
 * Date: 8/13/2016
 * Time: 6:03 PM
 */

class DeveloperScope implements Scope {

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(\Illuminate\Database\Eloquent\Builder $builder, \Illuminate\Database\Eloquent\Model $model)
    {
        // TODO: Implement apply() method.
        return $builder->where('is_admin_page',false);
    }
}