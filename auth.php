<?php

namespace General\Middleware;
require_once('Mid1.php');

class Middleware
{
    private $next = 0;
    public function called(\Closure $method, $request = null)
    {
        $middilewares = ['General\\Middleware\\Mid1'];
        $next = $method;
        foreach (array_reverse($middilewares) as $mid) {
            $mid = \Closure::fromCallable([new $mid(), 'handle']);
          $next = function($request) use ($mid, $next){
             return $mid($request, $next);
          };
        }
        return $next($request);
    }

}

class ex
{

    public function sum($request)
    {
        return $request."sum";
    }
}

$w = new ex();
$call = new Middleware();
$c = \Closure::fromCallable([$w, 'sum']);
echo $call->called($c, 'fun');
?>