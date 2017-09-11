<?php

namespace Kisphp\Services\Server\Plugins;

use Kisphp\Services\Server\AbstractServerManager;

class ApacheServer extends AbstractServerManager
{
    const SITES_AVAILABLE = '/etc/apache2/sites-available/';
    const SITES_ENABLED = '/etc/apache2/sites-enabled/';

    const VHOST_TPL = 'apache.conf.twig';
}
