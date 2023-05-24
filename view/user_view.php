<?php

use General\Session;
use General\Roles;

$user = Session::get('user');
$data = [
    'links' => [
        ['to' => react(''), 'label' => __v('home'), 'component' => 1],
        ['to' => react('goods/take'), 'label' => __v('Take Stock'), 'component' => 2],
    ],
    'urls' => [
        react('goods/take') => Route('goods/take'),
        react('logout') => Route('logout'),
    ],
    'content' => [
        'text' => __v('welcome message'),
        'header' => __v('welcome header')
    ],
    'footer' => [
        ['text' => __v('footer message'),]
    ],
    'update' => true,
    'redirect' => '',
    'server' => true,
    'default' => '/dashboard',
    'user' => ['name' => $user->name, 'lable' => __v('logout'), 'url' => Route('logout')],
];
if (Roles::has($user, 'admin')) {
    $data['links'] = array_merge($data['links'],
    [
        ['to' => react('goods/delivery'), 'label' => __v('deliver goods'), 'component' => 2],
        ['to' => react('goods/addtostore'), 'label' => __v('add to store'), 'component' => 2],
        ['to' => react('accounting'), 'label' => __v('Accounting'), 'component' => 3],
        ['to' => react('goods/show'), 'label' => __v('Goods'), 'component' => 3],
        ['to' => react('goods/quantity'), 'label' => __v('Quantity'), 'component' => 3],
    ]);
    $data['urls'] = array_merge($data['urls'],
        [
            react('goods/delivery') => Route('goods/delivery'),
            react('accounting') => Route('goods/accounting'),
            react('goods/show') => Route('goods/show'),
            react('goods/addtostore') => Route('goods/addtostore'),
            react('goods/quantity') => Route('goods/quantity'),
        ]);
}
return $data;