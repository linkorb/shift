<?php

namespace Shift\Common\Loader;

use Shift\Common\Model\ServiceDefinition;
use Shift\Common\Model\FunctionDefinition;

class ShiftLoader
{
    public function loadServiceDefinitionFile($filename)
    {
        $json = file_get_contents($filename);
        $data = json_decode($json, true);
        return $this->loadServiceDefinition($data, dirname($filename));
    }

    public function loadServiceDefinition($data, $baseDir)
    {
        $serviceDefinition = new ServiceDefinition();
        $serviceDefinition->setName($data['name']);
        foreach ($data['functions'] as $name=>$data) {
            $functionDefinition = $this->loadFunctionDefinition($data, $baseDir);
            $serviceDefinition->getFunctionDefinitions()->add($functionDefinition);
        }
        return $serviceDefinition;
    }

    public function loadFunctionDefinition($data, $baseDir)
    {
        $functionDefinition = new FunctionDefinition();
        if (isset($data['include'])) {
            $json = file_get_contents($baseDir . '/' . $data['include']);
            $includeData = json_decode($json, true);
            $data = array_merge_recursive($data, $includeData);
        }
        $functionDefinition->setName($data['name']);

        if (isset($data['execution'])) {
            $functionDefinition->setExecutionType($data['execution']['type']);
            $functionDefinition->setExecutionArguments($data['execution']);

        }

        if (isset($data['config'])) {
            $functionDefinition->setConfigSchema($data['config']);
        }
        if (isset($data['input'])) {
            $functionDefinition->setInputSchema($data['input']);
        }
        if (isset($data['output'])) {
            $functionDefinition->setOutputSchema($data['output']);
        }

        return $functionDefinition;
    }
}
