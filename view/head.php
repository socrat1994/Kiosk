<?php
return [
    'links' => [['to' => react('login/view'), 'label' => __v('sign in'), 'component' => 2],
        ['to' => react('register/view'), 'label' => __v('register'), 'component' => 2],
        ['to' => react(''), 'label' => __v('home'), 'component' => 1]],
    'content' => ['text' => __v('welcome message'),
        'header' => __v('welcome header')],
    'footer' => [
        ['text' => __v('footer message'),],],
    'urls' => [react('login/view') => Route('login/view'),
        react('register/view') => Route('register/view'),],
    'server' => true,
    'default' => '/dashboard',
];