<?php

namespace Kisphp\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ServerDetectCommand extends Command
{
    protected function configure()
    {
        $this->setName('server:detect')
            ->setDescription('Detect which server is installed')
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $process = new Process('sudo /etc/init.d/nginx status');
        $process->run();

        if (!empty($process->getOutput())) {
            $output->writeln('Server: <info>Nginx</info>');
        }

        $process = new Process('sudo /etc/init.d/apache2 status');
        $process->run();

        if (!empty($process->getOutput())) {
            $output->writeln('Server: <info>Apache2</info>');
        }
    }
}
