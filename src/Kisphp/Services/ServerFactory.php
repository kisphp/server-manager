<?php

namespace Kisphp\Services;

use Kisphp\Services\Server\Plugins\ApacheServer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ServerFactory
{
    const SERVER_APACHE = 'apache2';
    const SERVER_NGINX = 'nginx';

    /**
     * @var \Kisphp\Services\Server\Plugins\ApacheServer
     */
    protected $server;

    /**
     * @param string $serverName
     */
    public function __construct($serverName, InputInterface $input, OutputInterface $output, Filesystem $filesystem)
    {
        if ($serverName === self::SERVER_APACHE) {
            $this->server = new ApacheServer($input, $output, $filesystem);
        }
//        if ($serverName === self::SERVER_NGINX) {
//            $this->server = new ApacheServer($input, $output);
//        }
    }

    /**
     * @return \Kisphp\Services\Server\Plugins\ApacheServer
     */
    public function getServer()
    {
        return $this->server;
    }
}
