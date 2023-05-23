<?php

namespace General\Database;

class Database
{
    use Connection;

    private $connection;
    public $table;
    public $where = '1=1';
    public $sql = '';
    public $columns = '*';
    public $select = 'sqlget';
    public $joins = [];
    public $currentJoin;

    public function __construct()
    {
        try {
            $this->connection = $this->connect();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function CreateTable($table_name, $fields)
    {
        $sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema =? AND table_name=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$this->dbname, $table_name]);
        if ($stmt->fetchColumn() === 0) {
            $sql = "CREATE TABLE $table_name ($fields)";
            try {
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
                return "created successfully";
            } catch (PDOException $e) {
                return $table_name . ":" . $e->getMessage();
            }
        }
        return 'already exist';
    }

    public function migrate($tables)
    {
        foreach ($tables as $table_name => $fields) {
            $status[$table_name] = $this->CreateTable($table_name, $fields);
        }
        return $status;
    }

    public function getD()//need to update security
    {
        $sql = $this->sql;
        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            return $this->connection->errorInfo();
        } else {
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_OBJ);
        }
    }

    public function deleteD($column, $values)
    {
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $sql = 'DELETE FROM ' . $this->table . ' WHERE ' . $column . ' IN (' . $placeholders . ')';
        try {
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute($values);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function sqlGet()
    {
        $this->sql = 'SELECT ' . $this->columns . ' FROM ' . $this->table . ' WHERE ' . $this->where . ' ;';
        return $this;
    }

    public function sqlJoin()
    {
        $this->sql = 'SELECT ' . $this->columns . ' FROM ' . $this->table . ' AS t0';
        $i = 1;
        foreach ($this->joins as $join) {
            $this->sql .= ' INNER JOIN ' . $join->childT . ' AS t' . $i . ' ON t' . ($i - 1) .
                '.' . $join->parentId . ' = t' . $i . '.' . $join->childId . ' ';
            $i++;
        }
        $this->sql .= ' WHERE ' . $this->where;
    }

    public function table($name)
    {
        $this->table = $name;
        return $this;
    }

    public function columns($columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function where($where)
    {
        $this->where = $where;
        return $this;
    }

    public function santize($input)
    {
        return preg_replace('/[^0-9a-zA-Z_]/', '', $input);
    }

    public function p_insert($inputs)
    {
        $fields = array_keys($inputs[0]);
        $fields = array_map([$this, 'santize'], $fields);
        $fieldList = implode(',', $fields);
        $placeholders = [];
        $values = [];
        foreach ($inputs as $row) {
            $placeholderList = implode(',', array_fill(0, count($row), '?'));
            $placeholders[] = "($placeholderList)";
            $values = array_merge($values, array_values($row));
        }
        $placeholders = implode(',', $placeholders);
        $sql = 'INSERT INTO ' . $this->santize($this->table) . ' (' . $fieldList . ') VALUES ' . $placeholders;
        try {
            // Prepare and execute the query
            $stmt = $this->connection->prepare($sql);
            if ($stmt->execute($values)) {
                return $this->connection->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function p_update($inputs)
    {
        foreach ($inputs as $input) {
            $set = '';
            $values = array();
            foreach ($input['values'] as $field => $value) {
                $set .= $this->santize($field) . '=?,';
                $values[] = $value;
            }
            $set = rtrim($set, ',');
            $sql = 'UPDATE ' . $this->santize($this->table) . ' SET ' . $set . ' WHERE ' . $input['where'];
            try {
                $stmt = $this->connection->prepare($sql);
                if (!$stmt->execute($values)) {
                    return false;
                }
            } catch (PDOException $e) {
                throw $e;
            }
        }
        return true;
    }

    public function g_insert($table)
    {
        $inputs = Request::get();
        foreach ($inputs as $field => $input) {
            $rules[$field] = ['Text'];
        }
        $validation = new Validation($rules, $inputs);
        if (!$validation->valid) {
            return $validation->message;
        }
        try {
            $this->p_insert($validation->output);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function valid_str($inputs)
    {
        foreach ($inputs as $field => $input) {
            $rules[$field] = ['Text'];
        }
        $validation = new Validation($rules, $inputs);
        if (!$validation->valid) {
            return $validation->message;
        } else {
            return $validation->output;
        }
    }

    public function __destruct()
    {
        $this->disconnect();
    }
}
