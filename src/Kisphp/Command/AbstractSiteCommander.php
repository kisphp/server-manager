<?php

namespace Kisphp\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractSiteCommander extends Command
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    protected function executeOnlyForRoot()
    {
        if (posix_geteuid() !== 0) {
            $this->outputError('Please run this command with "sudo"');
            die;
        }
    }

    /**
     * @param string $message
     *
     * @return int
     */
    protected function outputError($message)
    {
        $this->output->writeln('<error> ' . $message . ' </error>');

        return 1;
    }

    /**
     * @param string $message
     */
    protected function comment($message)
    {
        $this->output->writeln('<comment>' . $message . '</comment>');
    }
}
