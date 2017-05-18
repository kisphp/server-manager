<?php

namespace Kisphp\Command\Nginx;

use Kisphp\Command\AbstractSiteCommander;
use Kisphp\Core\AbstractFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ActivateCommand extends AbstractSiteCommander
{
    const COMMAND = 'nginx:activate';

    protected function configure()
    {
        $this->setName(self::COMMAND)
            ->setDescription('Activate vhost')
            ->addArgument('directory', InputArgument::REQUIRED, 'Set directory name')
            ->addArgument('public_directory', InputArgument::OPTIONAL, 'Set public directory inside project', 'web')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->executeOnlyForRoot();

        $parameters = AbstractFactory::getParameters();

        $serverPath = $parameters['server_path'];

        $isSuccess = $this->createVhost($serverPath);

        if ($isSuccess === false) {
            return false;
        }

        $this->restartServer();

        $this->success('Domain ' . $this->input->getArgument('directory') . ' successfully activated');
    }

    /**
     * @param string $serverPath
     *
     * @return bool
     */
    protected function createVhost($serverPath)
    {
        $this->comment('Create vhost: ' . $this->input->getArgument('directory'));

        $twig = AbstractFactory::createTwig();

        $directory = $this->input->getArgument('directory');

        $params = [
            'server_path' => $serverPath,
            'directory' => $directory,
            'domain' => $directory,
            'public_directory' => $this->input->getArgument('public_directory'),
        ];

        $directoryPath = $serverPath . '/' . $directory;
        if (is_dir($directoryPath) === false) {
            $this->outputError($directoryPath . ' does not exists');

            return false;
        }

        $vhost = $twig->render('nginx/vhost-dev.twig', $params);

        $vhostTarget = $this->getNginxVhostTarget($directory);
        $symlinkTarget = $this->getNginxSymlinkTarget($directory);

        file_put_contents($vhostTarget, $vhost);

        if (is_file($symlinkTarget)) {
            unlink($symlinkTarget);
        }

        symlink($vhostTarget, $symlinkTarget);

        return true;
    }
}
