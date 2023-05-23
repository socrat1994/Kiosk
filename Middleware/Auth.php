<?php

namespace App\Middleware;

use General\Authenticate;
use General\Middleware;
use General\Request;
use General\Route;
use General\Session;

class Auth extends Middleware
{
    public static function handle(Request $request, \Closure $next, $method = null)
    {
        $user = Session::get('user')??false;
        if(!$user) {
            header('Location:' . Route(''));
            exit;
        }
        Authenticate::$user = $user;
        return $next($request);
    }
}