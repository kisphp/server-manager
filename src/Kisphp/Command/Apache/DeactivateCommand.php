<?php

namespace Kisphp\Command\Apache;

use Kisphp\Command\AbstractSiteCommander;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeactivateCommand extends AbstractSiteCommander
{
    const COMMAND = 'apache:deactivate';

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

        $vhostTarget = $this->getApacheVhostTarget($directory);
        $symlinkTarget = $this->getApacheSymlinkTarget($directory);

        if (is_file($symlinkTarget)) {
            $this->success('Remove symlink: ' . $symlinkTarget);
            unlink($symlinkTarget);
        }

        if (is_file($vhostTarget)) {
            $this->success('Remove file: ' . $vhostTarget);
            unlink($vhostTarget);
        }
    }
}
