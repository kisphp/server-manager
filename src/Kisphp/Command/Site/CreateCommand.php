<?php

namespace Kisphp\Command\Site;

use Kisphp\Core\AbstractFactory;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends AbstractSiteCommander
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
     * @return void|int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->executeOnlyForRoot();

        $parameters = AbstractFactory::getParameters();

        $serverPath = $parameters['server_path'];

        $projectDirectory = $serverPath . '/' . $this->input->getArgument('directory');

        if (is_dir($projectDirectory)) {
            return $this->outputError('Directory ' . $this->input->getArgument('directory') . ' already exists');
        }

        $this->createProjectDirectory($projectDirectory . '/' . $this->input->getArgument('public_directory'));

        $this->success('Site directory successfully created');

        if ($input->getOption('activate')) {
            $this->callActivateCommand();
        }
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

    /**
     * @return int
     */
    protected function callActivateCommand()
    {
        $command = $this->getApplication()->find(ActivateCommand::COMMAND);

        $arguments = [
            'command' => ActivateCommand::COMMAND,
            'directory' => $this->input->getArgument('directory'),
            'public_directory' => $this->input->getArgument('public_directory'),
        ];

        $greetInput = new ArrayInput($arguments);

        return $command->run($greetInput, $this->output);
    }
}
