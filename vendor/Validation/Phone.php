<?php

namespace General\Validation;

use General\Language;

class Phone extends Validation
{
    public static function valid($field, $phone, $rule)
    {
        if ($phone === '') {
            return ;
        }
        if (!preg_match('/^\+?\d{5,15}$/', $phone)) {
            Self::$message[$field][] = Language::t('phone', 'Validation');
            Self::$valid = false;
            return;
        }
        Self::$output[$field] = filter_var($phone, FILTER_SANITIZE_STRING);
    }
}