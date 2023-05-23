<?php

namespace General\Validation;

use General\Language;

class Password extends Validation
{
    public static function valid($field, $password, $rule)
    {
        if ($password !== Self::$inputs['confirmation']) {
            Self::$message[$field][] = Language::t('confirmation', 'Validation');
            Self::$valid = false;
            return;
        }
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            Self::$message[$field][] = Language::t('password', 'Validation');
            Self::$valid = false;
            return;
        }
        Self::$output[$field] = password_hash($password, PASSWORD_DEFAULT);
    }
}
