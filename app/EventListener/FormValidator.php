<?php

namespace App\EventListener;

use Core\ParameterWire;
use InvalidArgumentException;
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

        foreach($currentRule as $key => $func) {
            $value = $this->getRequestValue($key);
            $parameterMap = $this->buildParameterMap($value);

            $this->dispatchValidator($key, $func, $parameterMap);
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

    private function dispatchValidator($key, $func, ParameterMap $parameterMap)
    {
        if(is_string($func) || is_callable($func)) {
            $this->executeValidator($func, $parameterMap);
        } else if(is_array($func)) {
            foreach($func as $funcItem) {
                $this->executeValidator($funcItem, $parameterMap);
            }
        } else {
            throw new InvalidArgumentException(
                sprintf('Invalid validator type on [%s] in [%s]', $key, $this->proc->getName())
            );
        }
    }

    private function executeValidator($func, ParameterMap $parameterMap)
    {
        ProcFactory::getProc($func)->execWithParameterMap($parameterMap);
    }

    public static function setRule(array $rule)
    {
        self::$rule = $rule;
    }
}