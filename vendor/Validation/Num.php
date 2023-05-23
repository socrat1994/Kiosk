<?php

namespace General\Validation;

use General\Language;

class Num extends Validation
{
    public static function valid($field, $input, $rule)
    {
        if ($input === '') {
            return ;
        }
        $min = isset($rule[1]) ? $rule[1] : PHP_INT_MIN;
        $max = isset($rule[2]) ? $rule[2] : PHP_INT_MAX;
        if (!filter_var($input, FILTER_VALIDATE_INT)) {
            Self::$message[$field][] = Language::t('num', 'Validation');
            Self::$valid = false;
            return;
        }
        if ($input < $min) {
            Self::$message[$field][] = sprintf(Language::t('nummin', 'Validation'), $min);
            Self::$valid = false;
            return;
        }
        if ($input > $max) {
            Self::$message[$field][] = sprintf(Language::t('nummax', 'Validation'), $max);
            Self::$valid = false;
            return;
        }
        Self::$output[$field] = $input;
    }
}