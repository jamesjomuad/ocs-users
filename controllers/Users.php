<?php namespace Ocs\Users\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Ocs\Users\Models\User as UserModel;
use Ocs\Users\Models\Role as RoleModel;
use \Carbon\Carbon;
use Flash;
use Lang;
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
    public $context;

    public function __construct()
    {
        if(request()->slug=="ocs/users/users/trashed")
        {
            $this->listConfig = 'config_list_trash.yaml';
        }
        
        parent::__construct();

        BackendMenu::setContext('Ocs.Users', 'users', input('role') ? : 'users');

        $this->addCss("/plugins/ocs/users/assets/css/users.css");
    }

    public function index()
    {
        $this->pageTitle = 'Users';

        $this->asExtension('ListController')->index();
    }

    public function create()
    {
        $this->bodyClass = 'compact-container';

        $this->pageTitle = 'Users';

        $this->asExtension('FormController')->create();
    }

    public function update($recordId, $context = null)
    {
        $this->bodyClass = 'compact-container';
        
        $this->pageTitle = 'Edit User';

        return $this->asExtension('FormController')->update($recordId, $context);
    }

    public function trashed()
    {
        # Menu Context
        BackendMenu::setContext('Ocs.Users', 'users', 'trashed');

        # Page Title
        $this->pageTitle = 'Trashed';

        # Set Configs
        $this->listConfig = 'config_list_trash.yaml';

        $this->asExtension('ListController')->index();
    }

    public function formBeforeCreate($model)
    {
        if(RoleModel::where('code',input('role'))->first())
        {
            $roleId = RoleModel::where('code',input('role'))->first()->id;
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

        if($this->action=='trashed')
        {
            return $query->onlyTrashed();
        }

        return $query->where('id',0);
    }

    public function listExtendColumns($list)
    {
        // Add parameter to list url
        if(!str_contains($list->recordUrl,'?role='))
        {
            $list->recordUrl = $list->recordUrl . '?role=' . input('role');
        }
    }

    public function formExtendModel($model)
    {

        if(is_null($model->ocsUser))
        {
            $model->ocsUser = new UserModel;
        }

        return $model;
    }

    public function onHardDelete()
    {
        /*
         * Delete records
         */
        $records = User::withTrashed()->find(input('checked'));

        if ($records->count()) {
            foreach ($records as $record) {
                $record->forceDelete();
            }

            Flash::success(Lang::get('backend::lang.list.delete_selected_success'));
        }
        else {
            Flash::error(Lang::get('backend::lang.list.delete_selected_empty'));
        }

        return $this->listRefresh();
    }

    public static function getSideMenus()
    {
        $role = RoleModel::all();

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
        $sideMenu->put('trashed', [
            'label' => 'Trashed',
            'url'   => \Backend::url('ocs/users/users/trashed'),
            'icon'  => 'icon-trash'
        ]);

        return $sideMenu->all();
    }

}
