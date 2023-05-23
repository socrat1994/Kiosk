<?php

namespace General;

class Route
{
    public static $routes = [];
    public static $route;
    private static $instance;
    private static $domain;
    public static $middles;

    public static function set($route, $path)
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        self::$routes[$route] = $path;
        self::$route = $route;
        return self::$instance;
    }

    public static function get($route)
    {
        return self::$routes[$route] ?? false;
    }

    public static function view($view)
    {
        Self::direction();
        include __DIR__ . '/../view/head.php';
    }

    public static function data($source)
    {
        $data = include __DIR__ . '/../view/' . $source . '.php';
        return $data;
    }

    public static function redirect($url = '')
    {
        if ($url === '') {
            $url = Session::get('beforelogin');
        } else {
            $url = Self::url($url);
        }
        header('Location:' . $url);
    }

    public static function group($middles, $controller, $paths)
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        foreach ($paths as $route => $method) {
            self::$routes[$route] = [$controller, $method];
            Self::$middles[$route] = $middles;
        }
        return self::$instance;
    }

    public static function redirectapp($url = '')
    {
        if ($url === '') {
            $url = Session::get('beforelogin');
        } else {
            $url = Self::url($url);
        }
        header('Location:' . $url);
    }

    public static function authView($view)
    {
        Self::direction();
        include __DIR__ . '/../view/auth/' . $view . '_view.php';
    }

    public static function direction()
    {
        if (Language::get() === 'ar') {
            echo '<style>input{direction:rtl}</style>';
        }
    }

    public static function url($url)
    {
        if (!isset(Self::$domain)) {
            $env = EnvReader::getInstance();
            Self::$domain = $env->get('DOMAIN');
        }
        return Self::$domain . $url;
    }

    public function middleware($middles)
    {
        $route = Self::$route;
        Self::$middles[$route] = $middles;
    }

    public static function call($class, $method, $request, $middlewares)
    {
        $middle = new Middleware();
        $middle->setmid($middlewares);
        return $middle->build($class, $method, $request);
    }

    public static function goBack()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            echo "No previous page found";
        }
        exit;
    }
}