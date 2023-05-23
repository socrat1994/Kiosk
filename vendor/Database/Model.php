<?php

namespace General\Database;

class Model extends Database
{
    public $setJoin = array('setChildJoin', 'setParentJoin');

    public function __construct($className = false)
    {
        parent::__construct();
        if ($className) {
            if (!isset($className::$table_name)) {
                $className = str_replace('App\\Model\\', '', $className);
                $this->table = strtolower($className) . 's';
            } else {
                $this->table = $className::$table_name;
            }
        }
    }

    public function foreign($table, $key)
    {
        $this->select = 'sqlJoin';
        $setJoin =  $this->setJoin[0];
        $this->currentJoin = new \stdClass();
        $this->$setJoin($table, $key);
        return $this;
    }

    public function setChildJoin($table, $key)
    {
        $this->currentJoin->childId = $key;
        $this->currentJoin->childT = $table;
    }

    public function setParentJoin($table, $key)
    {
        $this->currentJoin->table = $table;
        $this->currentJoin->parentId = $key;
    }

    public function getParent()
    {
        $this->setJoin = ['setParentJoin', 'setChildJoin'];
        return $this;
    }

    public function references($table, $key, $chainToModel = '')
    {
        $setJoin = $this->setJoin[1];
        $this->$setJoin($table, $key);
        $this->joins[] = $this->currentJoin;
        if($chainToModel === '') {
            return $this;
        }
        return $this->copyProperties($chainToModel);
    }

    public static function get($where = 'true')
    {
        $columns = get_called_class()::$seen ?? '*';
        $model = new Model(get_called_class());
        $model->where($where);
        $model->columns($columns);
        $method = $model->select;
        $model->$method();
        $results = $model->getD();
        return $results;
    }

    public function get_($where = 'true', $className = false)
    {
        $columns = $this->columns;//get_called_class()::$seen??'*';
        $this->__construct($className ?? get_called_class());
        $this->where($where);
        $this->columns($columns);
        $method = $this->select;
        $this->$method();
        $results = $this->getD();
        return $results;
    }

    public static function insert($inputs)
    {
        $model = new Model(get_called_class());
        return $model->p_insert($inputs);
    }

    public static function delete($inArray, $column = 'id')
    {
        $model = new Model(get_called_class());
        return $model->deleteD($column, array_values($inArray));
    }

    public static function update($inputs)
    {
        $model = new Model(get_called_class());
        return $model->p_update($inputs);
    }

    public function copyProperties($paste)
    {
        $newInstance = new $paste;
        foreach ($this as $property => $value) {
            $newInstance->$property = $value;
        }
        return $newInstance;
    }
}