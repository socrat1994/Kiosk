<?php

namespace General\Validation;

use General\Language;

class Required extends validation
{
    public static function valid($field, $input, $rule)
    {
        if ($input === '') {
            Self::$message[$field][] = Language::t('required', 'Validation');
            Self::$valid = false;
        } else {
            Self::$output[$field] = $input;
        }
    }
}