<?php

namespace Kisphp\Command\Site;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

abstract class AbstractSiteCommander extends Command
{
    const APACHE_SITES_AVAILABLE = '/etc/apache2/sites-available/';
    const APACHE_SITES_ENABLED = '/etc/apache2/sites-enabled/';

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

    /**
     * @param string $message
     */
    protected function success($message)
    {
        $this->output->writeln('<info>' . $message . '</info>');
    }

    protected function restartApache()
    {
        $this->comment('Restart apache server');

        $process = new Process('/etc/init.d/apache2 restart');
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    /**
     * @param string $directory
     *
     * @return string
     */
    protected function getVhostTarget($directory)
    {
        return self::APACHE_SITES_AVAILABLE . '/' . $directory . '.conf';
    }

    /**
     * @param string $directory
     *
     * @return string
     */
    protected function getSymlinkTarget($directory)
    {
        return self::APACHE_SITES_ENABLED . '/' . $directory . '.conf';
    }
}
