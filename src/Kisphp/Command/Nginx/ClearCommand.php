<?php

namespace Kisphp\Command\Nginx;

use Kisphp\Command\AbstractSiteCommander;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCommand extends AbstractSiteCommander
{
    protected function configure()
    {
        $this->setName('nginx:clear')
            ->setDescription('Remove all nginx virtual hosts')
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->executeOnlyForRoot();

        $this->removeNginx(AbstractSiteCommander::NGINX_SITES_ENABLED);
        $this->removeNginx(AbstractSiteCommander::NGINX_SITES_AVAILABLE);

        $this->restartServer();
    }

    /**
     * @param $targetDirectory
     */
    protected function removeNginx($targetDirectory)
    {
        $files = scandir($targetDirectory);

        foreach ($files as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $symlinkTarget = $this->getNginxSymlinkTarget($item);
            $this->removeFile($symlinkTarget);
            $symlinkTarget = $this->getNginxVhostTarget($item);
            $this->removeFile($symlinkTarget);
        }
    }
}
