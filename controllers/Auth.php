<?php namespace Jlab\Users\Controllers;

use BackendMenu;
use BackendAuth;
use Flash;
use Backend;
use Backend\Classes\Controller;
use Backend\Models\AccessLog;
use Backend\Models\User as UserModel;


/**
 * Auth Back-end Controller
 */
class Auth extends Controller
{

    public function login()
    {
        // Authenticate user by credentials
        $user = BackendAuth::authenticate([
            'login'     => @UserModel::findUserByEmail(input('login'))->login ?? post('login'),
            'password'  => post('password')
        ]);

        if(BackendAuth::check())
        {
            // Log the sign in event
            AccessLog::add($user);

            Flash::success('Login Successful!');

            // Redirect to the intended page after successful sign in
            return Backend::redirectIntended('backend');
        }
    }

}
