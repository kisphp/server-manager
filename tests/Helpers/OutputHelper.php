<?php

namespace tests\Helpers;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OutputHelper implements OutputInterface
{
    protected $buffer = [];

    /**
     * @return array
     */
    public function getBuffer()
    {
        return $this->buffer;
    }

    public function write($messages, $newline = false, $options = 0)
    {
        $this->buffer[] = $messages;
    }

    public function writeln($messages, $options = 0)
    {
        $this->buffer[] = $messages;
    }

    public function setVerbosity($level)
    {
        // TODO: Implement setVerbosity() method.
    }

    public function getVerbosity()
    {
        // TODO: Implement getVerbosity() method.
    }

    public function isQuiet()
    {
        // TODO: Implement isQuiet() method.
    }

    public function isVerbose()
    {
        // TODO: Implement isVerbose() method.
    }

    public function isVeryVerbose()
    {
        // TODO: Implement isVeryVerbose() method.
    }

    public function isDebug()
    {
        // TODO: Implement isDebug() method.
    }

    public function setDecorated($decorated)
    {
        // TODO: Implement setDecorated() method.
    }

    public function isDecorated()
    {
        // TODO: Implement isDecorated() method.
    }

    public function setFormatter(OutputFormatterInterface $formatter)
    {
        // TODO: Implement setFormatter() method.
    }

    public function getFormatter()
    {
        // TODO: Implement getFormatter() method.
    }
}
