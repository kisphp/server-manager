<?php

namespace Kisphp\Core;

use Kisphp\Kisdb;
use Symfony\Component\Yaml\Yaml;

abstract class DbFactory
{
    /**
     * @param string|null $databaseName
     *
     * @return Kisdb
     */
    public static function createDatabaseConnection($databaseName = null)
    {
        $params = self::getDbParameters();

        $db = Kisdb::getInstance();
        $db->enableDebug();
        $db->connect(
            $params['database.host'],
            $params['database.user'],
            $params['database.pass'],
            $databaseName
        );

        return $db;
    }

    /**
     * @return array
     */
    public static function getDbParameters()
    {
        $configContent = self::getConfigParameters();
        $config = Yaml::parse($configContent);

        return $config['parameters'];
    }

    /**
     * @return string
     */
    protected static function getConfigParameters()
    {
        $parametersPath = realpath(__DIR__ . '/../../../config/parameters.yml');

        return file_get_contents($parametersPath);
    }
}
