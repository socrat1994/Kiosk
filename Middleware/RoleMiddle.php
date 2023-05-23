<?php

namespace App\Middleware;

use General\Authenticate;
use General\Middleware;
use General\Request;
use General\Roles;
use General\Route;
use General\Session;
use App\Model\Role;

class RoleMiddle extends Middleware
{
    public static function handle(Request $request, \Closure $next, $method)
    {
        $roles = Roles::$roles[$method] ?? '';
        if (empty($roles)) {
            return $next($request);
        }
        $roles = explode(',', $roles);
        $user = Authenticate::$user;
        $userRoles = explode(',', $user->roles);
        foreach ($roles as $role) {
            if (in_array($role, $userRoles)) {
                return $next($request);
            }
        }
        header('Location:' . Route('authorization'));
        exit();
    }
}