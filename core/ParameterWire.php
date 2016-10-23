<?php

namespace Core;

use Prob\Handler\ParameterMap;
use Prob\Handler\ParameterInterface;

class ParameterWire
{
    private static $parameters = [];

    public static function appendParameter(ParameterInterface $key, $value)
    {
        self::$parameters[] = [
            'key' => $key,
            'value' => $value
        ];
    }

    public static function appendParameterCallback(LazyWiringParameterCallback $callback)
    {
        self::$parameters[] = $callback;
    }

    public static function injectParameter(ParameterMap $map)
    {
        $buildParameters = self::makeParameter();

        foreach ($buildParameters as $v) {
            $value = $v['value'] instanceof LazyWiringParameter ? $v['value']->exec() : $v['value'];
            $map->bindBy($v['key'], $value);
        }

        return $map;
    }

    private static function makeParameter()
    {
        $buildParameters = [];

        foreach (self::$parameters as $v) {
            if ($v instanceof LazyWiringParameterCallback) {
                $buildParameters = array_merge($buildParameters, $v->exec());
                continue;
            }

            $buildParameters[] = $v;
        }

        return $buildParameters;
    }

    public static function lazyCallback(callable $func)
    {
        return new LazyWiringParameterCallback($func);
    }

    public static function lazy(callable $parameter)
    {
        return new LazyWiringParameter($parameter);
    }
}
