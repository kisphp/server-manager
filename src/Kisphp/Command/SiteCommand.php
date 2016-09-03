<?php

namespace Kisphp\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SiteCommand extends Command
{
    const DESCRIPTION = 'Create new site';
    const COMMAND = 'site:create';

    protected function configure()
    {
        $this->setName(self::COMMAND)
            ->setDescription(self::DESCRIPTION)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('asd');
    }

}
