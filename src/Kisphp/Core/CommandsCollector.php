<?php

namespace Kisphp\Core;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Finder\Finder;

class CommandsCollector
{
    /**
     * @var array
     */
    protected $commands = [];

    public function __construct()
    {
        $this->commands = $this->getDefaultCommands();
    }

    /**
     * @param Command $command
     *
     * @return $this
     */
    public function addCommand(Command $command)
    {
        $this->commands[] = $command;

        return $this;
    }

    /**
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @return array
     */
    protected function getDefaultCommands()
    {
        $files = new Finder();
        $files
            ->in(__DIR__ . '/../Command/')
            ->name('*Command.php')
        ;

        $commands = [];
        foreach ($files as $command) {
            $className = str_replace('.php', '', $command->getFilename());
            $commandNamespace = sprintf('Kisphp\\Command\\%s', $className);

            $commands[] = new $commandNamespace();
        }

        return $commands;
    }
}
