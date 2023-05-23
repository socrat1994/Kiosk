<?php

use App\Model\Good;

$data = Good::get();
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
        "accessorKey" => "selling",
        "header" => __v("selling"),
        "size" => 140
    ],
    [
        "T" => "no",
        "accessorKey" => "cost",
        "header" => __v("cost"),
        "size" => 140,
    ],
];
return ['columns' => $columns,
    'data' => $data,
    'excepts' => [0],
    'delete' => true,
    'edit' => true,
    'addbutton' => __v('add'),
    'submitto' => Route('goods/crud'),
];