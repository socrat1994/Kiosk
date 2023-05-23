<?php

namespace App\Model;

use General\Database\Model;

class Quantity extends Model
{
    public static $table_name = 'quantities';

    public function kiosk()
    {
        return $this->getParent()->foreign('quantities', 'kiosk_id')->references('kiosks', 'id');
    }
}