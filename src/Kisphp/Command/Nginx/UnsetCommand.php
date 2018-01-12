<?php

namespace Kisphp\Command\Nginx;

use Kisphp\Command\AbstractSiteCommander;
use Kisphp\Core\AbstractFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UnsetCommand extends AbstractSiteCommander
{
    protected function configure()
    {
        $this->setName('nginx:unset')
            ->setDescription('Unset all nginx virtual hosts')
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

        $params = AbstractFactory::getParameters();

        $toolDirectory = $this->getToolDirectory();

        $files = scandir($params['server_path']);

        foreach ($files as $item) {
            if ($item === '.' || $item === '..' || $item === $toolDirectory) {
                continue;
            }
            $symlinkTarget = $this->getNginxSymlinkTarget($item);

            $this->removeFile($symlinkTarget);
        }

        $this->restartServer();
    }

    /**
     * @return string
     */
    protected function getToolDirectory()
    {
        return basename(realpath(__DIR__ . '/../../../../'));
    }
}
