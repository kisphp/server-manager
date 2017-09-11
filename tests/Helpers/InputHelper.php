<?php

namespace tests\Helpers;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class InputHelper implements InputInterface
{
    protected $params = [];

    public function getFirstArgument()
    {
        // TODO: Implement getFirstArgument() method.
    }

    public function hasParameterOption($values, $onlyParams = false)
    {
        // TODO: Implement hasParameterOption() method.
    }

    public function getParameterOption($values, $default = false, $onlyParams = false)
    {
        // TODO: Implement getParameterOption() method.
    }

    public function bind(InputDefinition $definition)
    {
        // TODO: Implement bind() method.
    }

    public function validate()
    {
        // TODO: Implement validate() method.
    }

    public function getArguments()
    {
        return $this->params;
    }

    public function getArgument($name)
    {
        return $this->params[$name];
    }

    public function setArgument($name, $value)
    {
        $this->params[$name] = $value;

        return $this;
    }

    public function hasArgument($name)
    {
        return array_key_exists($name, $this->params);
    }

    public function getOptions()
    {
        // TODO: Implement getOptions() method.
    }

    public function getOption($name)
    {
        // TODO: Implement getOption() method.
    }

    public function setOption($name, $value)
    {
        // TODO: Implement setOption() method.
    }

    public function hasOption($name)
    {
        // TODO: Implement hasOption() method.
    }

    public function isInteractive()
    {
        // TODO: Implement isInteractive() method.
    }

    public function setInteractive($interactive)
    {
        // TODO: Implement setInteractive() method.
    }

}
