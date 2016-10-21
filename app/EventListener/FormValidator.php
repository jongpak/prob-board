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

    /**
     * @var ProcInterface
     */
    private $proc;

    /**
     * @var ServerRequestInterface
     */
    private $request;


    public function __construct(ProcInterface $proc, ServerRequestInterface $request)
    {
        $this->proc = $proc;
        $this->request = $request;
    }

    public function validate()
    {
        $currentRule = $this->getRuleOfCurrentRequest();

        /**
         * @var $func callable
         */
        foreach($currentRule as $key => $func) {
            $value = $this->getRequestValue($key);
            $parameterMap = $this->buildParameterMap($value);

            ProcFactory::getProc($func)->execWithParameterMap($parameterMap);
        }
    }

    private function getRuleOfCurrentRequest()
    {
        return isset(self::$rule[$this->proc->getName()])
            ? self::$rule[$this->proc->getName()]
            : [];
    }

    private function getRequestValue($key)
    {
        return isset($this->request->getParsedBody()[$key])
                ? $this->request->getParsedBody()[$key]
                : null;
    }

    private function buildParameterMap($value)
    {
        $parameterMap = new ParameterMap();
        $parameterMap->bindBy(new Named('value'), $value);
        ParameterWire::injectParameter($parameterMap);

        return $parameterMap;
    }

    public static function setRule(array $rule)
    {
        self::$rule = $rule;
    }
}