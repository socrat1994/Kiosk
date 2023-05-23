<?php

use General\Route;
use General\Request;

$warningMessage = null;
$prefix = '/dashboard/';
function customErrorHandler($errno, $errstr, $errfile, $errline)
{
    global $warningMessage;
    if ($errno == E_WARNING) {
        $warningMessage = "Warning: $errstr in $errfile on line $errline";
    }
    return true;
}
set_error_handler("customErrorHandler");
header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Credentials:true');
header('Content-Type: text/html');
require_once('Routes.php');
$path = str_replace($prefix, "", $_SERVER['REQUEST_URI']);
$path = strtok($path, '?');
$controller = Route::get($path);
if (!$controller) {
    $controller = ['Controllers\\HomeController', 'home'];
}
$class = new  $controller[0]();
$method = $controller[1];
try {
    $response = Route::call($class, $method, new Request(), Route::$middles[$path] ?? null);
    if (is_array($response) || is_object($response)) {
        if ($warningMessage) {
            $response['warning'] = $warningMessage;
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        if ($warningMessage) {
            echo $warningMessage;
        }
        echo $response;
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}

