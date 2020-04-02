<?php namespace Ocs\Users\Models;

use Model;
// use \Backend\Models\User;

/**
 * User Model
 */
class User extends Model
{
    // use \October\Rain\Database\Traits\SoftDelete;

    public $table = 'ocs_users';

    protected $guarded = ['*'];

    protected $fillable = [];

    public $rules = [];
}