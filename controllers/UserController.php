<?php

namespace Controllers;

use App\Model\Employee;
use General\Authenticate;
use General\Request;
use General\Route;
use General\Session;

class UserController
{
    public function registerView()
    {
        return Route::data('auth/register_view');//Route::authView('register');
    }

    public function register(Request $request)
    {
        $auth = new Authenticate();
        $register = $auth->register($request);
       if (!is_array($register)) {
           return redirectRt('login/view');
       }
       return $register;
    }

    public function loginView(Request $request)
    {
        return Route::data('auth/login_view');
    }

    public function login(Request $request)
    {
        $auth = new Authenticate();
        $login = $auth->login($request);
        if ($login === true) {
            $user = Session::get('user');
            [$employee] = Employee::get('user_id=' . $user->id);
            Session::set('kiosk_id', $employee->kiosk_id);
             header("Location:" . Route('user'));
        }
        return $login;
    }

    public function logout()
    {
        Session::unset('user');
        //header("Location:" . Route('t'));
        return Route::data('head');
    }
}