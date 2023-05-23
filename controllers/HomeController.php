<?php

namespace Controllers;

use General\Roles;
use General\Route;
use General\Session;

class HomeController
{
    public function __construct()
    {
       // Roles::$roles = ['index' => 'admin'];
    }
    public function index()
    {
        return Route::data('head');//Route::view('home');
    }

    function userIndex()
    {
        return Route::data('user_view');
    }

    function home()
    {
        include __DIR__ . '/../view/index.php';
    }
    function author()
    {
        return error('you do not have the permission to access this place');
    }
}