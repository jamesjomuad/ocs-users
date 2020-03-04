<?php namespace Ocs\Users\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Ocs\Users\Models\User;
use Ocs\Users\Models\Role;
use \Carbon\Carbon;
use October\Rain\Exception\ApplicationException;

/**
 * Users Back-end Controller
 */
class Users extends Controller
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

        BackendMenu::setContext('Ocs.Users', 'users', input('role') ? : 'users');

        $this->addCss("/plugins/ocs/users/assets/css/users.css");
    }

    public function test()
    {
        $this->pageTitle = 'Test';

        dd(
            \Backend\Models\User::find(3)
            // User::find(3)
            ->avatar
            // ->toArray()
        );
    }

    public function index()
    {
        $this->pageTitle = 'Users';

        $this->asExtension('ListController')->index();
    }

    public function update($recordId, $context = null)
    {
        $this->pageTitle = 'Edit User';

        return $this->asExtension('FormController')->update($recordId, $context);
    }

    public function formBeforeCreate($model)
    {
        if(Role::where('code',input('role'))->first())
        {
            $roleId = Role::where('code',input('role'))->first()->id;
            $model->role_id = $roleId;
        }
    }

    public function create_onSave($context = null)
    {
        parent::create_onSave($context);

        if(input('close') AND input('role'))
        {
            return \Backend::redirect(input('role') ? "ocs/users/users?role=".input('role') : '');
        }
    }

    public function update_onSave($context = null)
    {
        parent::update_onSave($context);

        if(input('close') AND input('role'))
        {
            return \Backend::redirect(input('role') ? "ocs/users/users?role=".input('role') : '');
        }
    }

    public function listExtendQuery($query)
    {
        if(input('role'))
        {
           return $query->withRole(input('role')); 
        }

        return $query->where('id',0);
    }

    public function listExtendColumns($list)
    {
        // avoid redandunt
        if(!str_contains($list->recordUrl,'?role='))
        {
            $list->recordUrl = $list->recordUrl . '?role=' . input('role');
        }
    }

    public static function getSideMenus()
    {
        $role = Role::all();

        $role = $role->pluck('name','code')->except(['publisher','developer']);

        $sideMenu = $role->map(function ($item, $key) {
            return [
                'label' => $item,
                'url'   => \Backend::url('ocs/users/users?role='.$key),
                'icon'  => 'icon-user-circle-o',
            ];
        });

        $sideMenu->put('role', [
            'label' => 'Role',
            'url'   => \Backend::url('ocs/users/role'),
            'icon'  => 'icon-user-plus'
        ]);

        return $sideMenu->all();
    }

}
