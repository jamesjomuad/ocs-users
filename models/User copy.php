<?php namespace Ocs\Users\Models;

use Model;

/**
 * User Model
 */
class User extends \Backend\Models\User
{
    use \October\Rain\Database\Traits\SoftDelete;

    public function scopeWithRole($query,$role)
    {
        return $query->whereHas('role',function($q) use($role) {
            $q->where('code',$role);
        });
    }

}
