<?php

namespace Core\ErrorReporter;

use \ErrorException;
use App\ViewResolver\ResponseResolver;
use Zend\Diactoros\Response\EmptyResponse;

class ErrorReporterService
{
    private $config = [];
    private $errorReporterInstances = [];

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    public function registerReport()
    {
        $this->constructErrorReporters();
        $this->registerErrorReporters();
    }

    private function registerErrorReporters()
    {
        set_exception_handler(function ($exception) {
            /**
             * @var ErrorReporterInterface $reporter
             */
            foreach ($this->errorReporterInstances as $reporter) {
                $reportResult = $reporter->report($exception);

                $this->initHttpResponseHeader($exception);

                if($this->config['displayErrors'] === true) {
                    echo $reportResult;
                }
            }
        });

        set_error_handler(function ($errno, $errstr, $errfile, $errline, array $errcontext) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
    }

    private function initHttpResponseHeader($exception)
    {
        $responseResolver = new ResponseResolver();

        if ($responseResolver->getResponseProxyInstance()->getResponse() === null) {
            $responseResolver
                ->getResponseProxyInstance()
                ->setResponse($this->getHttpErrorResponse($exception));
        }

        $responseResolver->resolve(null);
    }

    private function getHttpErrorResponse($exception)
    {
        $errorCode = 500;

        foreach ($this->config['errorCodes'] as $registedException => $code) {
            if ($registedException === get_class($exception)) {
                $errorCode = $code;
                break;
            }
        }

        return new EmptyResponse($errorCode);
    }

    private function constructErrorReporters()
    {
        $errorReporterInstances = [];

        foreach ($this->config['enableReporters'] as $reporterName) {
            $errorReporterInstances[] = $this->getErrorReporterInstance($reporterName);
        }

        $this->errorReporterInstances = $errorReporterInstances;
    }

    /**
     * @param  string $reporterName
     * @return ErrorReporterInterface
     */
    private function getErrorReporterInstance($reporterName)
    {
        $class = $this->config['reporters'][$reporterName]['class'];
        return new $class($this->config['reporters'][$reporterName]);
    }
}
