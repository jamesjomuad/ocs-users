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
        # Extend User
        UserModel::extend(function($model){
            # Extend Relations
            $model->hasOne['customer']  = [
                '\Bookrr\User\Models\Customers',
                'delete' => true
            ];
            $model->hasOne['staff']  = [
                '\Bookrr\User\Models\Staff',
                'delete' => true
            ];
            $model->hasMany['vehicles']  = [
                '\Bookrr\User\Models\Vehicle',
                'delete' => true
            ];

            # Extend Mehod
            $model->addDynamicMethod('isCustomer',function() use($model) {
                return $model->role->code=='customer' ? true : false;
            });

            return $model;
        });

        # Extend User fields
        UserController::extendFormFields(function($form, $model, $context){

            if(BackendAuth::getUser()->isCustomer())
            {
                $form->addTabFields([
                    'customer[phone]' => [
                        'label' => 'Phone Number',
                        'span'  => 'auto',
                        'tab'   => 'Profile'
                    ],
                    'customer[address]' => [
                        'label' => 'Company',
                        'span'  => 'auto',
                        'tab'   => 'Profile'
                    ],
                    'customer[gender]' => [
                        'label' => 'Gender',
                        'type'  => 'dropdown',
                        'span'  => 'auto',
                        'tab'   => 'Profile',
                        'emptyOption' => 'None',
                        'options'=> [
                            'male' => 'Male',
                            'female' => 'Female'
                        ]
                    ],
                    'customer[birth]' => [
                        'label' => 'Birthdate',
                        'type'  => 'datetimepicker',
                        'mode'  => 'date',
                        'span'  => 'auto',
                        'tab'   => 'Profile'
                    ]
                ]);
            }
            
        });
        
        # Event
        Event::listen('backend.page.beforeDisplay', function($controller, $action, $params) {

            if(BackendAuth::check() AND BackendAuth::getUser()->isCustomer())
            {
                $controller->addCss('/plugins/ocs/user/assets/css/customer.css');
            }

        });
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