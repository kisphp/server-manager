<?php

namespace Kisphp\Command;

use Kisphp\Core\AbstractFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SiteCreateCommand extends AbstractSiteCommander
{
    const DESCRIPTION = 'Create new site';
    const COMMAND = 'site:create';

    protected function configure()
    {
        $this->setName(self::COMMAND)
            ->setDescription(self::DESCRIPTION)
            ->addArgument('directory', InputArgument::REQUIRED, 'Set directory name')
            ->addArgument('public_directory', InputArgument::OPTIONAL, 'Set public directory inside project', 'web')
            ->addOption('activate', 'a', InputOption::VALUE_NONE, 'asd')
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

        if ($input->getOption('activate')) {
            $this->executeOnlyForRoot();
        }

        $parameters = AbstractFactory::getParameters();

        $serverPath = $parameters['server_path'];

        $projectDirectory = $serverPath . '/' . $this->input->getArgument('directory');

        if (is_dir($projectDirectory)) {
            $this->outputError('Directory ' . $this->input->getArgument('directory') . ' already exists');

            return;
        }

        $this->createProjectDirectory($projectDirectory . '/' . $this->input->getArgument('public_directory'));

        $this->success('Site directory successfully created');
    }

    /**
     * @param string $projectDirectory
     *
     * @return bool
     */
    protected function createProjectDirectory($projectDirectory)
    {
        $this->comment('Create project directory: ' . $projectDirectory);

        return mkdir($projectDirectory, 0755, true);
    }
}
