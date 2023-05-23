<?php

namespace General\Validation;

use General\Language;

class Validation
{
    public static $output = [];
    public static $valid = true;
    public static $message = [];
    public static $inputs;
    public $filterd ;
    public static $inArray = [];

    public function __construct($inputs = [], $fields_rules = [])
    {
        Self::$inputs = $inputs;
        foreach ($fields_rules as $field => $rules) {
            $this->validate($rules, $field);
        }
        return $this;
    }

    public function validate($rules, $field)
    {
        foreach ($rules as $rule) {
            $rule = explode(',', $rule);
            if (!isset(Self::$inputs[$field])) {
               Self::$valid = false;
               Self::$message[$field][] = Language::t('Required', 'Validation');
               return;
            }
            $input = Self::$inputs[$field] ?? [];
            $class = 'General\\Validation\\' . $rule[0];
            $class::valid($field, $input, $rule);
            isset(Self::$inArray[$rule[2] ?? null]) ? Self::$inArray[$rule[2]][Self::$inputs[$rule[3]]] = '1' : '';
        }
    }

    public function validateArray(array $inputs, callable $validation)
    {
        foreach ($inputs as $id => $input) {
            Self::$inputs = $input;
            $validation($input);
            if (!Self::$valid) {
                $messages[$id] = Self::message();
            } else {
                $outputs[] = Self::$output;
            }
            Self::$valid = true;
            Self::$message = [];
        }
        return ['outputs' => $outputs ?? [], 'messages' => $messages ?? []];
    }

    public function all(array $rules)
    {
        $inputs = $this->filterd ?? Self::$inputs;
        foreach ($inputs as $field => $value) {
            $this->validate($rules, $field);
        }
        return $this;
    }

    public function rules($genRules)
    {
        foreach (Self::$inputs as $key => $value) {
            isset($genRules[$key]) ? $rules[$key] = $genRules[$key] : '';
        }
        foreach ($rules as $field => $rule) {
            $this->validate($rule, $field);
        }
        return $this;
    }

    public function except(array $excs)
    {
        $array = Self::$inputs;
        foreach ($excs as $exc) {
            if (isset($array[$exc])) {
                unset($array[$exc]);
            }
        }
        $this->filterd = $array;
        return $this;
    }

    public function addValidation(array $inputs, array $fields_rules)
    {
        foreach ($fields_rules as $field => $rules) {
            $this->validate($rules, $field);
        }
        return $this;
    }

    public function in(&$inArray, $arrayKey, $key = false, $reverse = false)
    {
        if (empty(Self::$inArray[$arrayKey])) {
            $array_assoc = array();
            foreach ($inArray as $element) {
                $key ? $array_assoc[$element->$key] = '1' : $array_assoc[$element] = '1';
            }
            Self::$inArray[$arrayKey] = $array_assoc;
        }
        $reverse = $reverse ? ',1,' : ',,';
        return 'IN' . $reverse . $arrayKey . ',' . $key;
    }

    public static function message()
    {
        return ['errors' => Self::$message];
    }
}
