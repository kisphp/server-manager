<?php

namespace Kisphp\Command\Database;

use Kisphp\Core\AbstractFactory;
use Kisphp\Kisdb;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ImportCommand extends Command
{
    const DB_NAME = 'name';
    const DB_FILE = 'file';

    const DESCRIPTION = 'Import database schema';
    const COMMAND = 'db:import';

    /**
     * @var Kisdb
     */
    protected $db;

    protected function configure()
    {
        $this->setName(self::COMMAND)
            ->setDescription(self::DESCRIPTION)
            ->addArgument(self::DB_NAME, InputArgument::REQUIRED, 'Database name')
            ->addArgument(self::DB_FILE, InputArgument::REQUIRED, 'Schema file')
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
        $output->writeln('<info>' . self::DESCRIPTION . '</info>');

        $dbName = $input->getArgument(self::DB_NAME);
        $filename = $input->getArgument(self::DB_FILE);

        $this->db = AbstractFactory::createDatabaseConnection(null);

        if ($this->db === false) {
            return;
        }

        $command = AbstractFactory::createMysqlImportCommand($dbName, $filename);

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            $output->writeln('<error>' . $process->getErrorOutput() . '</error>');

            return;
        }

        $output->writeln(sprintf('Imported database <info>%s</info>', $dbName));

        $output->writeln($process->getOutput());
    }
}
