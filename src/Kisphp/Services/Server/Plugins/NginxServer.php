<?php

namespace Kisphp\Services\Server\Plugins;

use Kisphp\Services\Server\AbstractServerManager;

class NginxServer extends AbstractServerManager
{
    const SITES_AVAILABLE = '/etc/nginx/sites-available/';
    const SITES_ENABLED = '/etc/nginx/sites-enabled/';

    const VHOST_TPL = 'nginx.twig';

    const SERVER_NAME = 'nginx';
}
