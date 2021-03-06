<?php

namespace Kisphp\Command\Nginx;

use Kisphp\Command\AbstractSiteCommander;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeactivateCommand extends AbstractSiteCommander
{
    const COMMAND = 'nginx:deactivate';

    protected function configure()
    {
        $this->setName(self::COMMAND)
            ->setDescription('Deactivate vhost')
            ->addArgument('directory', InputArgument::REQUIRED, 'Directory name')
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
        $this->input = $input;
        $this->output = $output;

        $this->executeOnlyForRoot();

        $this->removeVhost();

        $this->restartServer();

        $this->success('Domain ' . $this->input->getArgument('directory') . ' successfully deactivated');
    }

    protected function removeVhost()
    {
        $this->comment('Remove vhost: ' . $this->input->getArgument('directory'));

        $directory = $this->input->getArgument('directory');

        $vhostTarget = $this->getNginxVhostTarget($directory);
        $symlinkTarget = $this->getNginxSymlinkTarget($directory);

        $this->removeFile($symlinkTarget);
        $this->removeFile($vhostTarget);
    }
}
