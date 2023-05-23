<?php

namespace App\Model;

use General\Database\Model;

class StockTaker extends Model
{
    public static  $table_name = 'stock_takers';

    public function accountings()
    {
        return $this->foreign('accountings', 'taker_id')->references('stock_takers', 'id');
    }
}