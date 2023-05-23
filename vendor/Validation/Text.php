<?php

namespace General\Validation;

use General\Language;

class Text extends Validation
{
    public static function valid($field, $text, $rule)
    {
        if ($text === '') {
            return ;
        }
        $min = isset($rule[1]) ? $rule[1] : 0;
        $max = isset($rule[2]) ? $rule[2] : PHP_INT_MAX;
        if (strlen($text) < $min) {
            Self::$message[$field][] = sprintf(Language::t('textmin', 'Validation'), $min);
            Self::$valid = false;
            return;
        }
        if (strlen($text) > $max) {
            Self::$message[$field][] = sprintf(Language::t('textmax', 'Validation'), $max);
            Self::$valid = false;
            return;
        }
        Self::$output[$field] = filter_var($text, FILTER_SANITIZE_STRING);
    }
}