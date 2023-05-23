<?php
$lang = __lang();
$dictionary = __('view');
return [
    'form' => [
        'submit' => $dictionary['submit'],
        'head' => $dictionary['sign in'],
        'submitto' => Route('login'),
    ]
    ,
    'inputs' => [
        [
            'column' => 2,
            'label' => $dictionary['phone'],
            'type' => "text",
            'name' => "phone"
        ],
        [
            'column' => 3,
            'label' => $dictionary["password"],
            'type' => "password",
            'name' => "password",
            'bname' => 'toggle1',
            'btype' => 'button',
            'dic' => ['show' => $dictionary['show'], 'hid' => $dictionary['hide']]
        ]
    ]
];