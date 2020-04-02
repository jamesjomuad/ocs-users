<?php namespace Ocs\Users;

use Backend;
use System\Classes\PluginBase;
use Backend\Models\User as UserModel;
use Backend\Models\UserRole;
use Backend\Controllers\Users as UserController;
use Event;



class Plugin extends PluginBase
{

    public $elevated = true;

    public function pluginDetails()
    {
        return [
            'name'        => 'Ocs User',
            'description' => 'Ocs extended user fields.',
            'author'      => 'ocs',
            'icon'        => 'icon-leaf'
        ];
    }

    public function boot()
    {
        // Avoid run on artisan or cron
        if(!app()->runningInBackend()){
            return;
        }

        Event::listen('translator.beforeResolve', function ($key, $replaces, $locale) {
            switch ($key) {
                case 'backend::lang.account.login_placeholder':
                    return 'Username/Email';
                case 'backend::lang.account.password_placeholder':
                    return 'Password';
                case 'backend::lang.user.menu_label':
                    return 'Users';
                case 'backend::lang.user.menu_description':
                    return 'Manage all users';
            }
        });

        UserModel::extend(function($model){
            # Relations
            $model->hasOne['ocsUser']  = [
                'Ocs\Users\Models\User'
            ];

            # Extend Mehod
            $model->addDynamicMethod('findUserByEmail',function($email) use($model)
            {
                return $model->where('email',$email)->first();
            });

            $model->addDynamicMethod('scopeWithRole',function($query,$role)
            {
                return $query->whereHas('role',function($q) use($role) {
                    $q->where('code',$role);
                });
            });
            

            return $model;
        });

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