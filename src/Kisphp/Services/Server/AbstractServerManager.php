<?php

namespace Kisphp\Services\Server;

use Kisphp\Core\AbstractFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    public function createVhost($serverPath)
    {
        $twig = AbstractFactory::createTwig();

        $directory = $this->input->getArgument('directory');
        $publicDirectory = $this->input->getArgument('public_directory');

        $vhost = $twig->render('template', [
            'server_path' => $serverPath,
            'directory' => $directory,
            'domain' => $directory,
            'public_directory' => $publicDirectory,
        ]);

        $vhostTarget = $this->createServerVhostTarget($directory);
        $symlinkTarget = '';

        file_put_contents($vhostTarget, $vhost);

        if (is_file($symlinkTarget)) {
            unlink($symlinkTarget);
        }

        symlink($vhostTarget, $symlinkTarget);
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
}