<?php

namespace General;

class Middleware
{
    public $middlewares = [];
    //public $Route_middles = //['auth' => 'App\\Middleware\\Auth'];

    public function build($class, $method, $request)
    {
        $next = \Closure::fromcallable([new $class(), $method]);
        foreach(array_reverse($this->middlewares) as $middleware)
        {
            $next = function ($request) use ($middleware, $next, $method) {
                return $middleware::handle($request, $next, $method);
            };
        }
        return $next($request);
    }

    public function setmid($middlewares)
    {
        $Route_middles = include(__DIR__ . '/../Middleware/kernel.php');
        foreach($Route_middles as $mid => $middle){
            if(array_search($mid, $middlewares??[]) !== false){
                $this->middlewares[] = $middle;
            }
        }
    }
}