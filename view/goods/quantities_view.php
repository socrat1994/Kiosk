<?php

use App\Model\Good;
use App\Model\Quantity;

$goods = new Good(Good::class);
$data = $goods->columns('t2.name as kiosk,t1.quantity as quantity,t0.name as good')
    ->quantities(Quantity::class)
    ->kiosk()->get_();

$columns = [
    [
        "T" => "no",
        "accessorKey" => 'kiosk',
        "header" => __v('kiosk'),
        "size" => 200
    ],
    [
        "T" => "no",
        "accessorKey" => "good",
        "header" => __v("good"),
        "size" => 50
    ],
    [
        "T" => "no",
        "accessorKey" => "quantity",
        "header" => __v("quantity"),
        "size" => 50,
    ],
];
return ['columns' => $columns,
    'data' => $data,
    'excepts' => [0],
];