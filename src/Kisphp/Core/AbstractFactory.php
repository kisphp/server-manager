<?php

namespace Kisphp\Core;

use Kisphp\Kisdb;
use Symfony\Component\Yaml\Yaml;

abstract class AbstractFactory
{
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
            $params['database_host'],
            $params['database_user'],
            $params['database_pass'],
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
}
