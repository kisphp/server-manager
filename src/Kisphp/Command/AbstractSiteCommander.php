<?php

namespace Kisphp\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

abstract class AbstractSiteCommander extends Command
{
    const APACHE_SITES_AVAILABLE = '/etc/apache2/sites-available/';
    const APACHE_SITES_ENABLED = '/etc/apache2/sites-enabled/';

    const NGINX_SITES_AVAILABLE = '/etc/nginx/sites-available/';
    const NGINX_SITES_ENABLED = '/etc/nginx/sites-enabled/';

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
            $this->outputError('Please run this command as "ROOT"');
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

    protected function restartServer()
    {
        $commandNamespace = get_called_class();
        $process = new Process('');

        if (preg_match('/Nginx/', $commandNamespace)) {
            $this->comment('Restart Nginx server');
            $process = $this->restartNginx();
        }

        if (preg_match('/Apache/', $commandNamespace)) {
            $this->comment('Restart Apache server');
            $process = $this->restartApache();
        }

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    /**
     * @return Process
     */
    private function restartApache()
    {
        $process = new Process('/etc/init.d/apache2 restart');
        $process->run();

        return $process;
    }

    /**
     * @return Process
     */
    private function restartNginx()
    {
        $process = new Process('/etc/init.d/nginx restart');
        $process->run();

        return $process;
    }

    /**
     * @param string $directory
     *
     * @return string
     */
    protected function getApacheVhostTarget($directory)
    {
        return self::APACHE_SITES_AVAILABLE . $directory . '.conf';
    }

    /**
     * @param string $directory
     *
     * @return string
     */
    protected function getApacheSymlinkTarget($directory)
    {
        return self::APACHE_SITES_ENABLED . $directory . '.conf';
    }

    /**
     * @param string $directory
     *
     * @return string
     */
    protected function getNginxVhostTarget($directory)
    {
        return self::NGINX_SITES_AVAILABLE . $directory;
    }

    /**
     * @param string $directory
     *
     * @return string
     */
    protected function getNginxSymlinkTarget($directory)
    {
        return self::NGINX_SITES_ENABLED . $directory;
    }
}
