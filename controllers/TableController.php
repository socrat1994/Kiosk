<?php

namespace Controllers;

use App\Model\Good;
use App\Model\Quantity;
use General\Request;
use General\Route;
use General\Session;
use General\Validation\Validation;

class TableController
{
    public $messages = [];
    public $valid;
    public $goods;
    public $previous_id;

    public function showQuantity()
    {
        return Route::data('goods/quantities_view');;
    }

    public function showGoods()
    {
        return Route::data('goods/goods_view');
    }

    public function insert($data)
    {
        $valid = &$this->valid;
        $len = count($data);
        $validation = $this->validation($data, [['Required', 'Num,0'], ['name' =>
            ['Required', 'Text', $valid->in($this->goods, 'a', 'name', true)]]]);
        !empty($validation['outputs']) ? $first_id = Good::insert($validation['outputs']) : '';
        $this->messages = $this->messages + $validation['messages'];
        $first_id ?? false ? $this->insertToQtys($first_id, $len) : '';
    }

    public function insertToQtys($first_id, $len)
    {
        $storeId = Session::get('kiosk_id');
        for ($i = $first_id; $i <= $first_id + $len - 1; $i++) {
            $inputs[] = ['good_id' => $i, 'kiosk_id' => $storeId, 'quantity' => 0];
        }
        Quantity::insert($inputs);
    }

    public function validation($data, $rules)
    {
        $valid = $this->valid;
        $validation = $valid->validateArray($data,
            function($input) use ($valid, $rules) {
                $valid->except(['name', 'id'])->all($rules[0])
                    ->addValidation($input, $rules[1]);
            });
        return $validation;
    }

    public function update($data)
    {
        $valid = &$this->valid;
        $validation = $valid->validateArray($data,
            function($input) use ($valid,) {
                $valid->rules(['name' => ['Text', $valid->in($this->goods, 'u', 'name', true)],
                    'cost' => ['Num,0'],
                    'selling' => ['Num,0']
                ])->addValidation([], ['id' => ['Required']]);
            });
        $outputs = $validation['outputs'];
        foreach ($outputs as $output) {
            $update[] = ['where' => 'id=' . $output['id'], 'values' => $output];
        }
        !empty($outputs) ? Good::update($update) : '';
        $this->messages = $this->messages + $validation['messages'];
    }

    public function delete($data)
    {
        $valid = $this->valid;
        $validation = $valid->validateArray($data,
            function($input) use ($valid) {
                $valid->except(['name'])->all(['Required',
                    $valid->in($this->goods, 'd', 'id')]);
            });
        !empty($validation['outputs']) ? Good::delete(toArray($data, 'id')) : '';
        $this->messages = $this->messages + $validation['messages'];
    }

    public function crud(Request $request)
    {
        $data = $request->getJson();
        $this->valid = new Validation();
        $goods = new Good(Good::class);
        $this->goods = $goods->columns('id,name')->get_();
        [$this->previous_id] = $goods->columns('MAX(id) as id')->get_();
        $a = empty($data['add']);
        $e = empty($data['edit']);
        $d = empty($data['delete']);
        !$a ? $this->insert($data['add']) : '';
        !$e ? $this->update($data['edit']) : '';
        !$d ? $this->delete($data['delete']) : '';
        if ($a and $e and $d) {
            return error('empty request');
        }
        if (empty($this->messages)) {
            return success();
        }
        return $this->messages;
    }
}