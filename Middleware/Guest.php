<?php

namespace App\Middleware;

use General\Authenticate;
use General\Middleware;
use General\Request;
use General\Session;

class Guest extends Middleware
{
    public static function handle(Request $request, \Closure $next, $method = null)
    {
        $user = Session::get('user');
        if($user) {
           header('Location:' . Route('user'));
           exit;
        }
        return $next($request);
    }
}