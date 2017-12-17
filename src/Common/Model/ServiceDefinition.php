<?php

namespace Shift\Common\Model;

class ServiceDefinition extends AbstractModel
{
    protected $name;
    protected $functionDefinitions = [];

    public function __construct()
    {
        $this->functionDefinitions = new \Collection\TypedArray(FunctionDefinition::class);
    }
}
