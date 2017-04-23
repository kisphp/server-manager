<?php

namespace Kisphp\Command\Database;

use Kisphp\Core\AbstractFactory;
use Kisphp\Kisdb;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    const DB_NAME = 'name';
    const DB_USER = 'user';
    const DB_PASS = 'pass';

    const DESCRIPTION = 'List databases and users privileges (slow)';
    const COMMAND = 'db:list';

    /**
     * @var Kisdb
     */
    protected $db;

    /**
     * @var array
     */
    protected $grants = [];

    protected function configure()
    {
        $this->setName(self::COMMAND)
            ->setDescription(self::DESCRIPTION)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>' . self::DESCRIPTION . '</info>');

        $this->db = AbstractFactory::createDatabaseConnection('mysql');

        if ($this->db->isConnected() === false) {
            $output->writeln('<error>Database connection failed</error>');

            return false;
        }

        $this->createGrantsTable($output);
        $this->createUsersTable($output);
    }

    /**
     * @param OutputInterface $output
     */
    protected function createGrantsTable(OutputInterface $output)
    {
        $users = $this->db->query('SELECT `User`, `Host` FROM `user`');
        while ($user = $users->fetch(\PDO::FETCH_ASSOC)) {
            $this->getGrantsForUser($user);
        }

        $this->renderGrantsTable($output);
    }

    /**
     * @param string $user
     */
    protected function getGrantsForUser($user)
    {
        $grants = $this->db->query(sprintf(
            'SHOW GRANTS for %s@%s',
            $user['User'],
            $user['Host']
        ));

        while ($gr = $grants->fetch(\PDO::FETCH_NUM)) {
            $this->grants[][] = $gr[0];
        }
    }

    /**
     * @param OutputInterface $output
     */
    protected function renderGrantsTable(OutputInterface $output)
    {
        $table = new Table($output);
        $table
            ->setHeaders([
                'Grant List',
            ])
            ->setRows(
                $this->grants
            )
        ;

        $table->render();
    }

    /**
     * @param OutputInterface $output
     */
    protected function createUsersTable(OutputInterface $output)
    {
        $userList = [];
        $users = $this->db->query('SELECT `Host`, `Db`, `User` FROM `mysql`.`db`');
        while ($user = $users->fetch(\PDO::FETCH_ASSOC)) {
            $userList[] = $user;
        }

        $this->renderUsersTable($output, $userList);
    }

    /**
     * @param OutputInterface $output
     * @param array $userList
     */
    protected function renderUsersTable(OutputInterface $output, array $userList)
    {
        $table = new Table($output);
        $table
            ->setHeaders([
                'Host',
                'Database',
                'User',
            ])
            ->setRows(
                $userList
            )
        ;

        $table->render();
    }
}
