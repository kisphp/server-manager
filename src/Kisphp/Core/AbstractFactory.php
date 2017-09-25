<?php

namespace Kisphp\Core;

use Symfony\Component\Yaml\Yaml;

abstract class AbstractFactory
{
    const DATABASE_HOST = 'database_host';
    const DATABASE_USER = 'database_user';
    const DATABASE_PASS = 'database_pass';

    /**
     * @param string|null $databaseName
     *
     * @return Kisdb
     */
    public static function createDatabaseConnection($databaseName = null)
    {
        $params = static::getParameters();

        $db = static::instantiateKisdb();
        $db->enableDebug();
        $db->connect(
            $params[static::DATABASE_HOST],
            $params[static::DATABASE_USER],
            $params[static::DATABASE_PASS],
            $databaseName
        );

        return $db;
    }

    /**
     * @return \Twig_Environment
     */
    public static function createTwig()
    {
        $loader = new \Twig_Loader_Filesystem(realpath(static::getRootPath() . '/app/Resources/templates/'));
        $twig = new \Twig_Environment($loader, [
            'cache' => false,
        ]);

        return $twig;
    }

    /**
     * @return array
     */
    public static function getParameters()
    {
        $configContent = static::getConfigParameters();
        $config = Yaml::parse($configContent);

        return $config['parameters'];
    }

    /**
     * @return string
     */
    protected static function getRootPath()
    {
        return realpath(__DIR__ . '/../../../');
    }

    /**
     * @return string
     */
    protected static function getConfigParameters()
    {
        $parametersPath = (static::getRootPath() . '/app/config/parameters.yml');

        return file_get_contents($parametersPath);
    }

    /**
     * @return Kisdb
     */
    protected static function instantiateKisdb()
    {
        return Kisdb::getInstance();
    }

    /**
     * @param string $dbName
     * @param string $filename
     *
     * @return string
     */
    public static function createMysqlImportCommand($dbName, $filename)
    {
        $params = static::getParameters();

        $command = '/usr/bin/mysql -h' . $params[static::DATABASE_HOST];
        $command .= ' -u' . $params[static::DATABASE_USER];
        if (!empty($params[static::DATABASE_PASS])) {
            $command .= ' -p' . $params[static::DATABASE_PASS];
        }
        $command .= ' ' . $dbName;
        $command .= ' < ' . $filename;

        return $command;
    }

    /**
     * @param string $dbName
     * @param string $filename
     *
     * @return string
     */
    public static function createMysqlExportCommand($dbName, $filename)
    {
        $params = static::getParameters();

        $command = '/usr/bin/mysqldump -h' . $params[static::DATABASE_HOST];
        $command .= ' -u' . $params[static::DATABASE_USER];
        if (!empty($params[static::DATABASE_PASS])) {
            $command .= ' -p' . $params[static::DATABASE_PASS];
        }
        $command .= ' ' . $dbName;
        $command .= ' > ' . $filename;

        return $command;
    }
}
