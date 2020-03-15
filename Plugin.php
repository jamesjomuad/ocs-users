<?php namespace Jlab\Users;

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
            'name'        => 'Jlab User',
            'description' => 'Jlab extended user fields.',
            'author'      => 'jlab',
            'icon'        => 'icon-leaf'
        ];
    }

    public function boot()
    {
        if(!app()->runningInBackend()){
            return;
        }

        Event::listen('translator.beforeResolve', function ($key, $replaces, $locale) {
            switch ($key) {
                case 'backend::lang.account.login_placeholder':
                    return 'Username';
                case 'backend::lang.account.password_placeholder':
                    return 'Password';
                case 'backend::lang.user.menu_label':
                    return 'Users';
                case 'backend::lang.user.menu_description':
                    return 'Manage all users';
            }
        });

    }

    public function registerComponents()
    {
        return [];
        return [
            'Jlab\User\Components\Register' => 'Register',
            'Jlab\User\Components\Login'    => 'Login'
        ];
    }

    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'jlab.user.some_permission' => [
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
                'url'         => Backend::url('jlab/users/users?role=operations'),
                'icon'        => 'icon-users',
                'permissions' => ['jlab.user.*'],
                'order'       => 920,

                'sideMenu' => \Jlab\Users\Controllers\Users::getSideMenus()
            ]
        ];
    }
}