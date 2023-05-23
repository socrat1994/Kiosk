<?php

namespace General;

use App\Model\Role;
use App\Model\User;
use General\Validation\Validation;


class Authenticate
{
    public static $user = [];

    public function login(Request $request)
    {
        $request = $request->get();
        $phone = $request['phone']??'()';
        [$users] = User::get("phone = '". $phone . "'");
        if(password_verify($request['password']??'<>', isset($users->password) ? $users->password : null)) {
            unset($users->password);
            [$roles] = Role::get("user_id =" . $users->id);
            $users->roles = $roles->roles;
            Self::$user = $users;
            Session::set('user', $users);
            return true;
        }
        return ['errors' => ['common' => __e('logerm')]];
    }

    public function register(Request $request)
    {
        $users = new User(User::class);
        $phones = $users->columns('phone')->get_();
        $rules = [
          'name' => ['Text,3,50', 'Required'],
          'phone' => ['Phone', 'Required'],
          'password' => ['Password'],
        ];
        $valid = new Validation($request->get(), $rules);
        $valid->addValidation($request->get(), ['phone' =>
            [$valid->in($phones, 'b', 'phone', true)]]);
        if (!Validation::$valid) {
            return Validation::message();
        }
        return User::insert([Validation::$output]);
    }
}
