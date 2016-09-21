<?php

namespace Kisphp\Command;

use Kisphp\Core\AbstractFactory;
use Kisphp\Kisdb;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseCreateCommand extends Command
{
    const DB_NAME = 'name';
    const DB_USER = 'user';
    const DB_PASS = 'pass';

    const DESCRIPTION = 'Create database and user';
    const COMMAND = 'db:create';

    /**
     * @var Kisdb
     */
    protected $db;

    protected function configure()
    {
        $this->setName(self::COMMAND)
            ->setDescription(self::DESCRIPTION)
            ->addArgument(self::DB_NAME, InputArgument::REQUIRED, 'Database name')
            ->addArgument(self::DB_USER, InputArgument::REQUIRED, 'Database username')
            ->addArgument(self::DB_PASS, InputArgument::REQUIRED, 'Database password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>' . self::DESCRIPTION . '</info>');

        $dbName = $input->getArgument(self::DB_NAME);
        $dbUser = $input->getArgument(self::DB_USER);
        $dbPass = $input->getArgument(self::DB_PASS);

        $this->db = AbstractFactory::createDatabaseConnection();

        $this->createDatabase($output, $dbName);
        $this->createUser($output, $dbUser, $dbPass);
        $this->grantUserAccess($output, $dbName, $dbUser);
    }

    /**
     * @param OutputInterface $output
     * @param string $databaseName
     */
    protected function createDatabase(OutputInterface $output, $databaseName)
    {
        $query = sprintf('CREATE DATABASE IF NOT EXISTS %s', $databaseName);
        $this->db->query($query);

        if ($output->isVerbose()) {
            $output->writeln($query);
        }
    }

    /**
     * @param OutputInterface $output
     * @param string $user
     * @param string $password
     */
    protected function createUser(OutputInterface $output, $user, $password)
    {
        $query = sprintf("CREATE USER IF NOT EXISTS '%s'@'%%' IDENTIFIED BY '%s'", $user, $password);
        $this->db->query($query);

        if ($output->isVerbose()) {
            $output->writeln($query);
        }
    }

    /**
     * @param OutputInterface $output
     * @param string $databaseName
     * @param string $user
     */
    protected function grantUserAccess(OutputInterface $output, $databaseName, $user)
    {
        $query = sprintf("GRANT ALL PRIVILEGES ON %s.* TO '%s'@'%%'", $databaseName, $user);
        $this->db->query($query);

        if ($output->isVerbose()) {
            $output->writeln($query);
        }
    }
}
