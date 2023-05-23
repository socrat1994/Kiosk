<?php

namespace Controllers;

//require_once(__DIR__.'/vendor/autoload.php');

//use General\Tables;
use General\Database;

class Message extends Database //implements Tables
{
    public function insert()
    {
        try {
            $this->g_insert('messages');
        } catch (\PDOException $e){
            throw $e;
        }


    }

    public function edit()
    {

    }
}
?>