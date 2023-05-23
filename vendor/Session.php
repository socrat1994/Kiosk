<?php

namespace General;

class Session
{
    public static function set($key, $value)
    {
        Self::session_start();
        $_SESSION[$key] = $value ;
    }

    public static function get($key)
    {
       Self::session_start();
        return $_SESSION[$key]??false;
    }

    public static function unset($key)
    {
        Self::session_start();
        unset($_SESSION[$key]);
    }

    public static function session_start()
    {
        if(session_status() == PHP_SESSION_NONE)
        {
            session_set_cookie_params([
                'lifetime' => 86400,
                'path' => '/',
                'httponly' => true,
            ]);
            session_name('Kiosk');
            session_start();
        }
    }
}