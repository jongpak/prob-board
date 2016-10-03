<?php

namespace App\Utils;

class FormUtility
{
    public static function getCheckboxOnItem($elementName, $parsedBody)
    {
        return isset($parsedBody[$elementName])
                ? array_keys(array_filter($parsedBody[$elementName], function ($e) { return $e === 'on'; }))
                : [];
    }
}
