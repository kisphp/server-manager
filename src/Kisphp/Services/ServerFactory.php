<?php

namespace Kisphp\Services;

use Kisphp\Services\Server\Plugins\ApacheServer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServerFactory
{
    const SERVER_APACHE = 'apache2';
    const SERVER_NGINX = 'nginx';

    protected $server;

    /**
     * @param string $serverName
     */
    public function __construct($serverName, InputInterface $input, OutputInterface $output)
    {
        if ($serverName === self::SERVER_APACHE) {
            $this->server = new ApacheServer($input, $output);
        }
//        if ($serverName === self::SERVER_NGINX) {
//            $this->server = new ApacheServer($input, $output);
//        }
    }


}