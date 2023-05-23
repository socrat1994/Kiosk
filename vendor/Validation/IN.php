<?php

namespace General\Validation;

use General\Language;

class IN extends Validation
{
    public static function valid($field, $input, $rule)
    {
        if ($input === '') {
            return ;
        }
        if ( $rule[1]??false xor isset(Self::$inArray[$rule[2]][$input])) {
            Self::$output[$field] = $input;
        } else {
            Self::$valid = false;
            Self::$message[$field][] = Language::t('IN', 'Validation');
        }
    }
}