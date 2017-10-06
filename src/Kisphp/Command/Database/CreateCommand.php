<?php

namespace Kisphp\Command\Database;

use Kisphp\Core\AbstractFactory;
use Kisphp\Kisdb;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
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
            ->addArgument(self::DB_USER, InputArgument::OPTIONAL, 'Database username')
            ->addArgument(self::DB_PASS, InputArgument::OPTIONAL, 'Database password')
        ;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>' . self::DESCRIPTION . '</info>');

        $dbName = $input->getArgument(self::DB_NAME);
        $dbUser = $input->getArgument(self::DB_USER);
        if (empty($dbUser)) {
            $dbUser = $dbName;
        }
        $dbPass = $input->getArgument(self::DB_PASS);
        if (empty($dbPass)) {
            $dbPass = $dbName;
        }

        $this->db = AbstractFactory::createDatabaseConnection(null);

        if ($this->db === false) {
            return;
        }

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
        $query = sprintf("CREATE USER '%s'@'%%' IDENTIFIED BY '%s'", $user, $password);
        $this->db->query($query);
        if ($output->isVerbose()) {
            $output->writeln($query);
        }

        $query = sprintf("CREATE USER '%s_ro'@'%%' IDENTIFIED BY '%s'", $user, $password);
        $this->db->query($query);
        if ($output->isVerbose()) {
            $output->writeln($query);
        }

        $this->displayError($output);
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

        $query = sprintf("GRANT SELECT, SHOW VIEW ON %s.* TO '%s_ro'@'%%'", $databaseName, $user);
        $this->db->query($query);
        if ($output->isVerbose()) {
            $output->writeln($query);
        }

        $this->displayError($output);
    }

    /**
     * @param OutputInterface $output
     */
    protected function displayError(OutputInterface $output)
    {
        $log = $this->db->getLog()->getLog();
        $last = end($log);

        if ($last['error_message'] !== null) {
            $output->writeln('<error>' . $last['error_message'] . '</error>');
        }
    }
}

#CREATE USER 'medics_ro'@'%' IDENTIFIED BY 'medics'
#GRANT SELECT, SHOW VIEW, PROCESS, REPLICATION CLIENT ON medics.* TO 'medics_ro'@'%'
