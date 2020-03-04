<?php namespace Ocs\Users\Models;

use Model;

/**
 * Role Model
 */
class Role extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'backend_user_roles';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [
        'name' => 'required|between:2,128|unique:backend_user_roles',
        'code' => 'unique:backend_user_roles',
    ];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = ['permissions'];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'users'         => [\Backend\Models\User::class, 'key' => 'role_id'],
        'users_count'   => [\Backend\Models\User::class, 'key' => 'role_id', 'count' => true]
    ];

    #
    #   Set Default Model Query
    #
    public function newQuery($excludeDeleted = true)
    {
        $query = parent::newQuery($excludeDeleted);
        $query->where('code','!=','developer');
        $query->where('code','!=','publisher');
        return $query;
    }

    public function filterFields($fields, $context = null)
    {
        if($fields->{'name'}->value)
        {
            $fields->{'code'}->value = str_slug($fields->{'name'}->value);
        }

        
        
        return $fields;
    }
}
