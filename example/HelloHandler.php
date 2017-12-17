<?php

namespace Shift\Example;

class HelloHandler
{
    public function helloPhpFunction(array $input, array $context)
    {
        $greeting = $input['greeting'];
        $name = $context['username'];

        $output = ['text' => $greeting . ', ' . $name . ' (' . $context['config']['color'] . ')'];
        return $output;
    }

}
