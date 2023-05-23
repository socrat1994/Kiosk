<?php
require_once('vendor/autoload.php');
require_once ('GlobalFunction.php');

use General\Route;
use Controllers\{GoodsController, HomeController, LangController, UserController, TableController};


Route::group(/*['auth', 'role']*/[], GoodsController::class,
[
     'goods/take'=> 'takeStockView',
     'goods/take/update' => 'takeStock',
     'goods/delivery' => 'deliveryView',
     'goods/deliver' => 'deliver',
     'goods/addtostore' => 'addToStoreView',
     'goods/addtostore/add' => 'addToStore',
     'goods/accounting' => 'accounting'
]);
Route::group([], TableController::class,[
    'goods/show' => 'showGoods',
    'goods/crud' => 'crud'
]);

Route::set('teste', [TableController::class , 'show']);
Route::set('lang', [LangController::class , 'change']);
Route::set('t', [HomeController::class , 'index']);//->middleware(['guest']);
Route::set('authorization', [HomeController::class , 'author']);
Route::set('user', [HomeController::class , 'userIndex']);//->middleware(['auth']);
Route::group(/*['guest']*/[], UserController::class,
[
    'register/view' => 'registerView',
    'register' => 'register',
    'login/view' => 'loginView',
    'login' => 'login',
]);
Route::set('logout', [UserController::class , 'logout']);



