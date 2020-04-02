<?php namespace Ocs\Users\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Role Back-end Controller
 */
class Role extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Ocs.Users', 'users', 'role');

        $this->addCss("/plugins/ocs/users/assets/css/animate.css");
        $this->addCss("/plugins/ocs/users/assets/css/role.css");
        $this->addJs("/plugins/ocs/users/assets/js/script.js");
    }

    public function formExtendFields($form)
    {
        /*
         * Add permissions tab
         */
        $form->addTabFields($this->generatePermissionsField());
    }

    protected function generatePermissionsField()
    {
        return [
            'permissions' => [
                'tab' => 'backend::lang.user.permissions',
                'type' => 'Backend\FormWidgets\PermissionEditor',
                'mode' => 'checkbox'
            ]
        ];
    }
}
