<?php
$lang = __lang();
$dictionary = __('view');
return [
    'form' => [
        'lang' => $lang,
        'submit' => $dictionary['submit'],
        'head' => $dictionary['register'],
        'submitto' => Route('register')
    ]
    ,
    'inputs' => [
        [
            'column' => 2,
            'label' => $dictionary['name'],
            'type' => "text",
            'name' => "name"
        ],
        [
            'column' => 2,
            'label' => $dictionary['phone'],
            'type' => "text",
            'name' => "phone"
        ],
        [
            'column' => 3,
            'label' => $dictionary['password'],
            'type' => "password",
            'name' => "password",
            'bname' => 'toggle1',
            'btype' => 'button',
            'dic' => ['show' => $dictionary['show'], 'hid' => $dictionary['hide']]
        ],
        [
            'column' => 3,
            'label' => $dictionary['confirmation'],
            'type' => "password",
            'name' => "confirmation",
            'bname' => 'toggle2',
            'btype' => 'button',
            'dic' => ['show' => $dictionary['show'], 'hid' => $dictionary['hide']]
        ]
    ]
];