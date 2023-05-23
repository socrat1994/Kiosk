<?php

namespace App\Model;

use General\Database\Model;

class User extends Model
{
    //public static $table_name = 'use';
    //public static $seen = 'name,phone,password';
    //public static $hidden = 'password';

    public function employees()
    {
        return $this->foreign('employees','user_id')->references('users', 'id');
    }

    public function takers($chainToModel = '')
    {
       return $this->foreign('stock_takers', 'user_id')->references('user', 'id', $chainToModel);
    }
}