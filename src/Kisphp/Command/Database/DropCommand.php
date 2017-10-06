<?php

namespace Kisphp\Command\Database;

use Kisphp\Core\AbstractFactory;
use Kisphp\Kisdb;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DropCommand extends Command
{
    const DB_NAME = 'name';
    const DB_USER = 'user';
    const DB_PASS = 'pass';

    const DESCRIPTION = 'Drop database and delete user';
    const COMMAND = 'db:drop';

    /**
     * @var Kisdb
     */
    protected $db;

    protected function configure()
    {
        $this->setName(self::COMMAND)
            ->setDescription(self::DESCRIPTION)
            ->addArgument(self::DB_NAME, InputArgument::REQUIRED, 'Database name')
            ->addArgument(self::DB_USER, InputArgument::OPTIONAL, 'Database username')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>' . self::DESCRIPTION . '</info>');

        $dbName = $input->getArgument(self::DB_NAME);
        $dbUser = $input->getArgument(self::DB_USER);
        if (empty($dbUser)) {
            $dbUser = $dbName;
        }

        $this->db = AbstractFactory::createDatabaseConnection(null);

        if ($this->db === false) {
            return;
        }

        $this->createDatabase($output, $dbName);
        $this->dropUser($output, $dbUser);
        $this->dropPrivileges($output, $dbUser);
    }

    /**
     * @param OutputInterface $output
     * @param string $databaseName
     */
    protected function createDatabase(OutputInterface $output, $databaseName)
    {
        $query = sprintf('DROP DATABASE IF EXISTS %s', $databaseName);
        $this->db->query($query);

        if ($output->isVerbose()) {
            $output->writeln($query);
        }
    }

    /**
     * @param OutputInterface $output
     * @param string $user
     */
    protected function dropUser(OutputInterface $output, $user)
    {
        $query = sprintf("DROP USER '%s'@'%%'", $user);
        $this->db->query($query);
        if ($output->isVerbose()) {
            $output->writeln($query);
        }

        $query = sprintf("DROP USER '%s_ro'@'%%'", $user);
        $this->db->query($query);
        if ($output->isVerbose()) {
            $output->writeln($query);
        }
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $user
     */
    protected function dropPrivileges(OutputInterface $output, $user)
    {
        $query = sprintf("REVOKE ALL PRIVILEGES ON *.* FROM '%s'@'%%'", $user);
        $this->db->query($query);
        if ($output->isVerbose()) {
            $output->writeln($query);
        }

        $query = sprintf("REVOKE ALL PRIVILEGES ON *.* FROM '%s_ro'@'%%'", $user);
        $this->db->query($query);
        if ($output->isVerbose()) {
            $output->writeln($query);
        }
    }
}
