<?php

namespace App\EventListener;

use Core\ParameterWire;
use Prob\Handler\Parameter\Named;
use Prob\Handler\ParameterMap;
use Prob\Handler\ProcFactory;
use Prob\Handler\ProcInterface;
use Psr\Http\Message\ServerRequestInterface;

class FormValidator
{
    private static $rule = [];

    public function validate(ProcInterface $proc, ServerRequestInterface $request)
    {
        if(isset(self::$rule[$proc->getName()]) === false) {
            return;
        }

        $rule = self::$rule[$proc->getName()];

        /**
         * @var $func callable
         */
        foreach($rule as $key => $func) {
            $value = isset($request->getParsedBody()[$key]) ? $request->getParsedBody()[$key] : null;

            $parameterMap = new ParameterMap();
            $parameterMap->bindBy(new Named('value'), $value);
            ParameterWire::injectParameter($parameterMap);

            ProcFactory::getProc($func)->execWithParameterMap($parameterMap);
        }
    }

    public static function setRule(array $rule) {
        self::$rule = $rule;
    }
}