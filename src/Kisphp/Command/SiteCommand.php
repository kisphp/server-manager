<?php

namespace Kisphp\Command;

use Kisphp\Core\AbstractFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SiteCommand extends AbstractSiteCommander
{
    const DESCRIPTION = 'Create new site';
    const COMMAND = 'site:create';

    const APACHE_SITES_AVAILABLE = '/etc/apache2/sites-available/';
    const APACHE_SITES_ENABLED = '/etc/apache2/sites-enabled/';

    protected function configure()
    {
        $this->setName(self::COMMAND)
            ->setDescription(self::DESCRIPTION)
            ->addArgument('directory', InputArgument::REQUIRED, 'Set directory name')
            ->addArgument('public_directory', InputArgument::OPTIONAL, 'Set public directory inside project', 'web')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->executeOnlyForRoot($output);

        $parameters = AbstractFactory::getParameters();

        $serverPath = $parameters['server_path'];

        $projectDirectory = $serverPath . '/' . $this->input->getArgument('directory');

        if (is_dir($projectDirectory)) {
            return $this->outputError('Directory ' . $this->input->getArgument('directory') . ' already exists');
        }

        $this->createProjectDirectory($projectDirectory . '/' . $this->input->getArgument('public_directory'));

        $this->createVhost($serverPath);

        $this->restartApache();

        $this->output->writeln('asd');
    }

    /**
     * @param string $projectDirectory
     *
     * @return bool
     */
    protected function createProjectDirectory($projectDirectory)
    {
        return mkdir($projectDirectory, 0755, true);
    }

    /**
     * @param $serverPath
     */
    protected function createVhost($serverPath)
    {
        $twig = AbstractFactory::createTwig();

        $tpl = $twig->loadTemplate('vhost.conf.twig');

        $vhost = $tpl->render([
            'server_path' => $serverPath,
            'directory' => $this->input->getArgument('directory'),
            'domain' => $this->input->getArgument('directory'),
            'public_directory' => $this->input->getArgument('public_directory'),
        ]);

        $vhostTarget = self::APACHE_SITES_AVAILABLE . '/' . $this->input->getArgument('directory') . '.conf';
        $symlinkTarget = self::APACHE_SITES_ENABLED . '/' . $this->input->getArgument('directory') . '.conf';
        file_put_contents($vhostTarget, $vhost);

        symlink($vhostTarget, $symlinkTarget);
    }

    protected function restartApache()
    {
        $process = new Process('/etc/init.d/apache2 restart');
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
