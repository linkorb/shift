<?php

namespace Shift\Common\Model;

use Collection\Identifiable;
use JsonSchema\Validator;
use JsonSchema\Constraints\Constraint;

class FunctionDefinition extends AbstractModel implements Identifiable
{
    protected $name;
    protected $configSchema;
    protected $inputSchema;
    protected $outputSchema;
    protected $executionType;
    protected $executionArguments = [];

    public function identifier()
    {
        return $this->getName();
    }

    protected function validateArray(array &$data, array $schema)
    {
        $validator = new Validator();

        $obj = (object)$data;
        $validator->validate(
            $obj,
            $schema,
            Constraint::CHECK_MODE_COERCE_TYPES|Constraint::CHECK_MODE_APPLY_DEFAULTS|Constraint::CHECK_MODE_EXCEPTIONS
        );
        $data = (array)$obj;

        return null;
    }

    public function validateInput(array $input)
    {
        if (!$this->getInputSchema()) {
            return true;
        }
        $this->validateArray($input, $this->getInputSchema());
    }

    public function validateConfig(array $config)
    {
        if (!$this->getConfigSchema()) {
            return true;
        }
        $this->validateArray($config, $this->getConfigSchema());
    }

    public function validateOutput(array $output)
    {
        if (!$this->getOutputSchema()) {
            return true;
        }
        $this->validateArray($output, $this->getOutputSchema());
    }
}
