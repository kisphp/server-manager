<?php

namespace Kisphp\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ServerCleanCommand extends AbstractSiteCommander
{
    const APACHE_LOCATION = '/etc/apache2/sites-enabled/';
    const NGINX_LOCATION = '/etc/nginx/sites-enabled/';

    protected function configure()
    {
        $this->setName('server:clean')
            ->setDescription('Remove all enabled websites')
            ->addArgument('type', InputArgument::OPTIONAL, 'Server type ((a)pache, (n)ginx)')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');

        if ($type === null || $type === 'a' || $type === 'apache') {
            $this->disableServerVhosts($output, self::APACHE_LOCATION);
            $this->restartApache();
        }
        if ($type === null || $type === 'n' || $type === 'nginx') {
            $this->disableServerVhosts($output, self::NGINX_LOCATION);
            $this->restartNginx();
        }
    }

    /**
     * @param OutputInterface $output
     */
    protected function disableServerVhosts(OutputInterface $output, $serverPath)
    {
        try {
            $dir = new \DirectoryIterator($serverPath);
        } catch (\UnexpectedValueException $e) {
            return $output->writeln('<error>' . $e->getMessage() . '</error>');
        }

        foreach ($dir as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            $this->removeFile($fileInfo->getRealPath());

            $output->writeln('Disable: <info>' . $fileInfo->getFilename() . '</info>');
        }
    }

    /**
     * @param string $filePath
     *
     * @return Process
     */
    protected function removeFile($filePath)
    {
        $process = new Process('sudo rm ' . $filePath);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process;
    }
}
