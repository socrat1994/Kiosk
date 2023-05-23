<?php

use App\Model\Good;
use General\Session;
if (!Session::get('kiosk_id')) {
    return error('you should be employee in this kiosk');
    exit();
}
$dictionary = __('view');
$data = [
    'form' => [
        'submit' => $dictionary['submit'],
        'head' => $dictionary['Take Stock'],
        'submitto' => Route('goods/take/update'),
    ]
];
$goods = new Good(Good::class);
$goods = $goods
    ->columns('t0.name,t0.id')
    ->quantities()
    ->get_('t1.kiosk_id=' . Session::get('kiosk_id'));
if (empty($goods)) {
    return error('this kiosk did not have goods yet');
}
foreach ($goods as $good) {
   $data['inputs'][] = [
        'column' => 2,
        'label' => $good->name,
        'type' => "number",
        'name' => $good->id
    ];
}
return $data;