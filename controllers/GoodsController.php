<?php

namespace Controllers;

use App\Model\Accounting;
use App\Model\Archive;
use App\Model\Good;
use App\Model\Quantity;
use App\Model\StockTaker;
use General\Request;
use General\Roles;
use General\Route;
use General\Session;
use General\Validation\Validation;

class GoodsController
{
    public $goods;
    public $userId;
    public $stockTakerId;
    public $inserted;

    public function __construct()
    {
        Roles::$roles = ['takeStockView' => 'admin,stocker', 'takeStock' => 'admin,stocker', 'deliveryView' => 'admin', 'accounting' =>
            'admin', 'addToStoreView' => 'admin', 'deliver' => 'admin', 'addToStore' => 'admin'];
    }

    public function takeStockView()
    {
        return Route::data('goods/take_view');
    }

    public function deliveryView()
    {
        $data = Route::data('goods/take_view');
        $data['form']['submitto'] = Route('goods/deliver');
        $data['form']['head'] = __v('deliver');
        $data['inputs'][] = [
            'column' => 2,
            'label' => __v('kiosk'),
            'type' => "number",
            'name' => 'kiosk_id'
        ];
        return $data;
    }

    public function accounting()
    {
        $data = Route::data('goods/accounting_view');
        return $data;
    }

    public function addToStoreView()
    {
        $data = Route::data('goods/take_view');
        $data['form']['submitto'] = Route('goods/addtostore/add');
        $data['form']['head'] = __v('add to store');
        return $data;
    }

    public function takeStock(Request $request)
    {
        $request = $request->get();
        $sales = $costs = 0;
        $kioskData = $this->data(Session::get('kiosk_id'));
        $rules = $map = [];
        foreach ($kioskData as $good) {
            $rules[$good->id] = ['Num,-1,' . $good->quantity];
            $map[$good->id] = $good;
        }
        $kioskData = $map;
        $validationResult = $this->validateRequest($request, $rules);
        if ($validationResult) {
            return $validationResult;
        }
        foreach ($request as $goodId => $quantity) {
            $data = isset($kioskData[$goodId]) ? $kioskData[$goodId] : null;
            if ($quantity !== '') {
                $current_quantity = $quantity;
                $def = $data->quantity - $current_quantity;
                if ($def) {
                    $sales += ($def) * $data->selling;
                    $costs += ($def) * $data->cost;
                    $net_income = $sales - $costs;
                    $updateQty[] = ['where' => 'good_id=' . $goodId, 'values' => ['quantity' => $quantity]];
                    $archive[] = ['taker_id' => $this->stockTakerId, 'good_id' => $goodId,
                        'last_quantity' => $data->quantity,
                        'current_quantity' => $current_quantity ?? $data->quantity,
                        'operation' => '-1'
                    ];
                }
            }
        }
        if ($sales) {
            $accountings[] = ['sales' => $sales, 'costs' => $costs, 'net_income' => $net_income, 'taker_id' => $this->stockTakerId];
            $a = Accounting::insert($accountings);
            $b = Archive::insert($archive);
            $c = Quantity::update($updateQty);
        }
        if ($a and $b and $c) {
            return success();
        }
        return error('some thing went wrong');
    }

    public function data($kiosk_id)
    {
        $this->goods = new Good(Good::class);
        $user = Session::get('user');
        $this->userId = $user->id;
        $this->stockTakerId = StockTaker::insert([['user_id' => $user->id, 'date' => date('Y-m-d')]]);
        $goods = $this->goods
            ->columns('t1.quantity,t0.id,t0.selling,t0.cost')
            ->quantities()
            ->get_('t1.kiosk_id=' . $kiosk_id);
        return $goods;
    }

    public function deliver(Request $request)
    {
        $request = $request->get();
        $storeData = $this->data(Session::get('kiosk_id'));
        $rules = $map = [];
        foreach ($storeData as $good) {
            $rules[$good->id] = ['Num,-1,' . $good->quantity];
            $map[$good->id] = $good;
        }
        $storeData = $map;
        $validationResult = $this->validateRequest($request,
            $rules + ['kiosk_id' => ['Required']]);
        if ($validationResult) {
            return $validationResult;
        }
        $kioskData = dataMap($this->data($request['kiosk_id']), 'good_id');
        $toUpdate = $this->insertToQts($kioskData, $request);
        if (empty($toUpdate) and !$this->inserted) {
            return error('wrong kiosk id');
        }
        foreach ($toUpdate as $goodId => $quantity) {
            if ($goodId === 'kiosk_id') {
                break;
            }
            $storeQuantity = $storeData[$goodId]->quantity;
            if ($quantity) {
                $kioskQuantity = $kioskData[$goodId]->quantity;
                $deliverQuantity = $quantity;
                $kioskQuantityAf = $kioskQuantity + $deliverQuantity;
                $storeQuantityAf = $storeQuantity - $deliverQuantity;
                $where = 'good_id=' . $goodId . ' AND kiosk_id=';
                $updateStore[] = ['where' => $where . Session::get('kiosk_id'), 'values' => ['quantity' => $storeQuantityAf]];
                $updateKiosk[] = ['where' => $where . $request['kiosk_id'], 'values' => ['quantity' => $kioskQuantityAf]];
                $storeArchive[] = ['taker_id' => $this->stockTakerId, 'good_id' => $goodId,
                    'last_quantity' => $storeQuantity,
                    'current_quantity' => $storeQuantityAf ?? $storeQuantity,
                    'operation' => $request['kiosk_id']
                ];
            }
        }
        $a = $b = $c = false;
        !empty($storeArchive) ? $a = Archive::insert($storeArchive) : '';
        $a and !empty($updateStore) ? $b = Quantity::update($updateStore) : '';
        $b and !empty($updateKiosk) ? $c = Quantity::update($updateKiosk) : '';
        if ($a and $b and $c) {
            return success();
        }
        return error('something went wrong');
    }

    public function insertToQts($kioskData, $request)
    {
        $kioskId = $request['kiosk_id'];
        foreach ($request as $goodId => $quantity) {
            if($goodId !== 'kiosk_id') {
                if (isset($kioskData[$goodId])) {
                    $toUpdate[$goodId] = (int)$quantity;
                } else {
                    $toInsert[] = ['good_id' => (int) $goodId, 'kiosk_id' => (int)$kioskId, 'quantity' => (int)$quantity];
                }
            }
        }
        !empty($toInsert??[]) ? $this->inserted = Quantity::insert($toInsert) : '';
        return $toUpdate??[];
    }

    public function addToStore(Request $request)
    {
        $request = $request->get();
        $validationResult = $this->validateRequest($request);
        if ($validationResult) {
            return $validationResult;
        }
        $kiosk_id = Session::get('kiosk_id');
        $storeData = $this->data($kiosk_id);
        $storeQuantities = [];
        foreach ($storeData as $row) {
            $storeQuantities[$row->id] = $row->quantity;
        }
        foreach ($request as $goodId => $quantityToAdd) {
            ($quantityToAdd === '') ? $quantityToAdd = 0 : '';
            $storeQuantityAf = $storeQuantities[$goodId] + $quantityToAdd;
            $where = 'good_id=' . $goodId . ' AND kiosk_id=';
            if ($quantityToAdd) {
                $updateStore[] = ['where' => $where . $kiosk_id, 'values' =>
                    ['quantity' => $storeQuantityAf]];
            }
            $archive[] = ['taker_id' => $this->stockTakerId, 'good_id' => $goodId,
                'last_quantity' => $storeQuantities[$goodId],
                'current_quantity' => $storeQuantityAf,
                'operation' => 0
            ];
        }
        !empty($archive) ? $a = Archive::insert($archive) : '';
        !empty($updateStore) ? $b = Quantity::update($updateStore) : '';
        if ($a and $b) {
            return success();
        }
        return error('something went wrong');
    }

    function validateRequest($request, $additionalValidations = [])
    {
        $val = new Validation($request);
        $val->addValidation($request, $additionalValidations);
        if (!Validation::$valid) {
            $messages = Validation::$message;
            $messages['common'] = __v('look above for wrong insertion');
            return ['errors' => $messages];
        }
        return null;
    }
}
