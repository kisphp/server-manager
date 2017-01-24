<?php

namespace tests\Core\Helpers;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DummyCommand extends Command
{
    protected function configure()
    {
        $this->setName('dummy:name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
