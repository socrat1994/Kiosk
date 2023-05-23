<?php

namespace Controllers;

use General\Language;
use General\Request;
use General\Route;
use General\Session;

class LangController
{
    function change()
    {
        $request = Request::get();
       Language::set($request['lang']);
        if (Session::get('user')) {
            $source = 'user_view';
        } else {
            $source = 'head';
        }
        return Route::data($source);
    }
}