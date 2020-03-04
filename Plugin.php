<?php namespace Ocs\Users;

use Backend;
use System\Classes\PluginBase;
use Backend\Models\User as UserModel;
use Backend\Models\UserRole;
use Backend\Controllers\Users as UserController;
use BackendAuth;
use Event;


class Plugin extends PluginBase
{

    public $elevated = true;

    public function pluginDetails()
    {
        return [
            'name'        => 'Bookrr User',
            'description' => 'Bookrr extended user fields.',
            'author'      => 'bookrr',
            'icon'        => 'icon-leaf'
        ];
    }

    public function boot()
    {

    }

    public function registerComponents()
    {
        return [];
        return [
            'Bookrr\User\Components\Register' => 'Register',
            'Bookrr\User\Components\Login'    => 'Login'
        ];
    }

    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'ocs.user.some_permission' => [
                'tab' => 'user',
                'label' => 'Some permission'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'users' => [
                'label'       => 'Users',
                'url'         => Backend::url('ocs/users/users?role=operations'),
                'icon'        => 'icon-users',
                'permissions' => ['ocs.user.*'],
                'order'       => 920,

                'sideMenu' => \Ocs\Users\Controllers\Users::getSideMenus()
            ]
        ];
    }
}