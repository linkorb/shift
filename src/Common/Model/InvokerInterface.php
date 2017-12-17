<?php

namespace Shift\Common\Model;

interface InvokerInterface
{
    public function invoke($functionName, array $input);
}
