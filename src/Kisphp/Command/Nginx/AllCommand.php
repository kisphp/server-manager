<?php

namespace Kisphp\Command\Nginx;

use Kisphp\Command\AbstractSiteCommander;
use Kisphp\Core\AbstractFactory;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AllCommand extends AbstractSiteCommander
{
    protected function configure()
    {
        $this->setName('nginx:all')
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

        $command = $this->getApplication()->get('nginx:activate');

        foreach ($files as $item) {
            if ($item === '.' || $item === '..' || $item === $toolDirectory) {
                continue;
            }

            $arguments = [
                'command' => 'nginx:activate',
                'directory' => $item,
                '--no-restart' => true,
            ];

            $commandInput = new ArrayInput($arguments);
            $command->run($commandInput, $output);
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
