<?php

namespace hipanel\modules\integrations\helpers;

use Closure;
use Tuck\Sort\Sort;

class ConfigSort
{
    public static function anyConfigs()
    {
        return Sort::chain()->asc(self::byDefault());
    }

    public static function byDefault(): Closure
    {
        $order = ['name', 'client_id', 'currency', 'commission', 'login', 'password', 'key2', 'key3'];

        return function ($attribute) use ($order) {
            if (($key = array_search($attribute, $order, true)) !== false) {
                return $key;
            }

            return INF;
        };
    }
}
