<?php

namespace App\Model;

use General\Database\Model;

class Good extends Model
{
    public function quantities($chainToModel ='')
    {
        return $this->foreign('quantities','good_id')->references('goods', 'id', $chainToModel);
    }
}