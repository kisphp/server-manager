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
        $configPath = realpath(__DIR__ . '/../../../config/parameters.yml');
        $config = Yaml::parse(file_get_contents($configPath));

        return $config['parameters'];
    }
}
