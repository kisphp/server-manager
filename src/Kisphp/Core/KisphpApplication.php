<?php

namespace Kisphp\Core;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

class KisphpApplication
{
    const APP_NAME = 'Kisphp';
    const APP_VERSION = '1.0.0';
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var CommandsCollector
     */
    protected $commandsCollector;

    /**
     * @param CommandsCollector $commandsCollector
     */
    public function __construct(CommandsCollector $commandsCollector)
    {
        $this->app = new Application(self::APP_NAME, self::APP_VERSION);
        $this->commandsCollector = $commandsCollector;
    }

    /**
     * @param Command $command
     *
     * @return $this
     */
    public function registerCommand(Command $command)
    {
        $this->app->add($command);

        return $this;
    }

    /**
     * @return $this
     */
    protected function registerCommandsList()
    {
        foreach ($this->commandsCollector->getCommands() as $command) {
            $this->registerCommand($command);
        }

        return $this;
    }

    /**
     * @return Application
     */
    public function boot()
    {
        $this->registerCommandsList();

        return $this->app;
    }
}
