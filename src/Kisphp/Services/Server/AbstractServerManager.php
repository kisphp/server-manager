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

    public function createVhost($serverPath)
    {
        $vhost = $this->generateVhostFile($serverPath);

        $directory = $this->input->getArgument('directory');

        $vhostTarget = $this->createServerVhostTarget($directory);
        $symlinkTarget = '';

        $this->output->writeln($vhostTarget);
        $this->output->writeln($symlinkTarget);

        $this->fs->dumpFile($vhostTarget, $vhost);
        $this->fs->symlink($vhostTarget, $symlinkTarget);
    }

    protected function createServerVhostTarget($directory)
    {
        return static::SITES_AVAILABLE . $directory . '.conf';
    }

    protected function createSymlinkTarget($directory)
    {
        return static::SITES_ENABLED . $directory . 'conf';
    }

    protected function createVhostName($directory)
    {
        return '--asd--';
    }

    public function removeVhost()
    {
    }

    public function restartServer()
    {
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
