<?php

namespace Kisphp\Command\Site;

use Kisphp\Core\AbstractFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ActivateCommand extends AbstractSiteCommander
{
    const COMMAND = 'site:activate';

    protected function configure()
    {
        $this->setName(self::COMMAND)
            ->setDescription('Activate vhost')
            ->addArgument('directory', InputArgument::REQUIRED, 'Set directory name')
            ->addArgument('public_directory', InputArgument::OPTIONAL, 'Set public directory inside project', 'web')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->executeOnlyForRoot();

        $parameters = AbstractFactory::getParameters();

        $serverPath = $parameters['server_path'];

        $this->createVhost($serverPath);

        $this->restartApache();

        $this->success('Domain ' . $this->input->getArgument('directory') . ' successfully activated');
    }

    /**
     * @param string $serverPath
     */
    protected function createVhost($serverPath)
    {
        $this->comment('Create vhost: ' . $this->input->getArgument('directory'));

        $twig = AbstractFactory::createTwig();

        $tpl = $twig->loadTemplate('vhost.conf.twig');

        $directory = $this->input->getArgument('directory');

        $vhost = $tpl->render([
            'server_path' => $serverPath,
            'directory' => $directory,
            'domain' => $directory,
            'public_directory' => $this->input->getArgument('public_directory'),
        ]);

        $vhostTarget = $this->getVhostTarget($directory);
        $symlinkTarget = $this->getSymlinkTarget($directory);

        file_put_contents($vhostTarget, $vhost);

        if (is_file($symlinkTarget)) {
            unlink($symlinkTarget);
        }

        symlink($vhostTarget, $symlinkTarget);
    }
}
