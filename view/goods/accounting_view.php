<?php

use App\Model\StockTaker;
use App\Model\User;

$columns = [
    [
        "T" => "i"
    ],
    [
        "T" => "no",
        "accessorKey" => 'name',
        "header" => __v('name')
    ],
    [
        "T" => "no",
        "accessorKey" => 'date',
        "header" => __v('date')
    ],
    [
        "T" => "no",
        "accessorKey" => "sales",
        "header" => __v("sales"),
        "size" => 140
    ],
    [
        "T" => "no",
        "accessorKey" => "costs",
        "header" => __v("costs"),
        "size" => 140,
        'enableEditing'=> false,
    ],
    [
        "T" => "no",
        "accessorKey" => 'net_income',
        "header" => __v('net_income')
    ],
];

$user = new User(User::class);
$data = $user->columns('t1.id, t0.name, t1.date, t2.sales, t2.costs, t2.net_income')
    ->takers(Stocktaker::class)->accountings()->get_();

return ['columns' => $columns, 'data' => $data];