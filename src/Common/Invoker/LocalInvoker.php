<?php

namespace Shift\Common\Invoker;

use Shift\Common\Model\InputInterface;
use Shift\Common\Model\InvokerInterface;
use Shift\Common\Model\ContextInterface;
use Shift\Common\Model\FunctionDefinition;
use Shift\Common\Model\ServiceDefinition;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use RuntimeException;

class LocalInvoker implements InvokerInterface
{
    protected $serviceDefinition;
    protected $context;

    public function __construct(ServiceDefinition $serviceDefinition, array $context)
    {
        $this->context = $context;
        $this->serviceDefinition = $serviceDefinition;
    }

    public function invoke($functionName, array $input)
    {
        if (!$this->serviceDefinition->getFunctionDefinitions()->hasKey($functionName)) {
            throw new RuntimeException("Undefined function: " . $functionName);
        }

        $functionDefinition = $this->serviceDefinition->getFunctionDefinitions()->get($functionName);

        $functionDefinition->validateInput($input);
        $functionDefinition->validateConfig($this->context['config']);

        switch ($functionDefinition->getExecutionType()) {
            case 'php';
                $handler = $functionDefinition->getExecutionArguments()['handler'];
                $part = explode('::', $handler);
                if (count($part)!=2) {
                    throw new RuntimeException("Invalid handler: " . $handler);
                }
                $className = $part[0];
                $methodName = $part[1];

                $obj = new $className;
                $output = $obj->{$methodName}($input, $this->context);
                break;

            case 'exec':
                $template = $functionDefinition->getExecutionArguments()['template'];

                $command = $template;
                foreach ($input as $key=>$value) {
                    if (is_string($value)) {
                        $command = str_replace('{$input.' . $key . '}', $value, $command);
                    }
                }
                foreach ($this->context as $key=>$value) {
                    if (is_string($value)) {
                        $command = str_replace('{$context.' . $key . '}', $value, $command);
                    }
                }
                $process = new Process($command);
                $process->run();
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }
                $output = [
                    'command' => $command,
                    'stdout' => $process->getOutput(),
                    'stderr' => $process->getErrorOutput(),
                    'exitCode' => $process->getExitCode(),
                ];
                break;
            default:
                throw new RuntimeException("Unsupported execution type: " . $functionDefinition->getExecutionType());
        }

        $functionDefinition->validateOutput($output);
        return ['output' => $output];
    }
}
