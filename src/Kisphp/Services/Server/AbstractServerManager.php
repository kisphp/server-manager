<?php

namespace Kisphp\Services\Server;

use Kisphp\Core\AbstractFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

abstract class AbstractServerManager
{
    const SITES_AVAILABLE = '';
    const SITES_ENABLED = '';

    const VHOST_TPL = '';

    const SERVER_NAME = '';

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Symfony\Component\Filesystem\Filesystem $fs
     */
    public function __construct(InputInterface $input, OutputInterface $output, Filesystem $fs)
    {
        $this->input = $input;
        $this->output = $output;
        $this->fs = $fs;
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    public function getFs()
    {
        return $this->fs;
    }

    /**
     * @param string $serverPath
     */
    public function createVhost($serverPath)
    {
        $vhost = $this->generateVhostFile($serverPath);

        $directory = $this->input->getArgument('directory');

        $vhostTarget = $this->createServerVhostTarget($directory);
        $symlinkTarget = $this->createSymlinkTarget($directory);

        $this->output->writeln('Create Vhost: <info>' .$vhostTarget . '</info>');
        $this->fs->dumpFile($vhostTarget, $vhost);
        $this->output->writeln('Create Symlink: <info>' .$symlinkTarget . '</info>');
        $this->fs->symlink($vhostTarget, $symlinkTarget);
    }

    public function removeVhost()
    {

    }

    /**
     * @return string
     */
    public function restartServer()
    {
        return '/etc/init.d/' . static::SERVER_NAME . ' restart';
    }

    /**
     * @param string $directory
     *
     * @return string
     */
    protected function createServerVhostTarget($directory)
    {
        $trans = [
            'apache' => $directory,
            'nginx' => $directory,
            '.twig' => '',
        ];

        $hostFile = str_replace(array_keys($trans), $trans, static::VHOST_TPL);

        return static::SITES_AVAILABLE . $hostFile;
    }

    /**
     * @param string $directory
     *
     * @return string
     */
    protected function createSymlinkTarget($directory)
    {
        $trans = [
            'apache' => $directory,
            'nginx' => $directory,
            '.twig' => '',
        ];

        $hostFile = str_replace(array_keys($trans), $trans, static::VHOST_TPL);

        return static::SITES_ENABLED . $hostFile;
    }

    /**
     * @param string $serverPath
     *
     * @return string
     */
    private function generateVhostFile($serverPath)
    {
        $twig = AbstractFactory::createTwig();

        $directory = $this->input->getArgument('directory');
        $publicDirectory = $this->input->getArgument('public_directory');

        $vhostFileContent = $twig->render(static::VHOST_TPL, [
            'server_path' => $serverPath,
            'directory' => $directory,
            'domain' => $directory,
            'public_directory' => $publicDirectory,
        ]);

        return $vhostFileContent;
    }
}
