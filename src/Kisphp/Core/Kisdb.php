<?php

namespace Kisphp\Core;

use Kisphp\Kisdb as KisphpDatabase;

class Kisdb extends KisphpDatabase
{
    /**
     * @return bool
     */
    public function isConnected()
    {
        return (bool) $this->isConnected;
    }
}
