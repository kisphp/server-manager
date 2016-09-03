<?php

namespace tests\Core\Helpers;

use Kisphp\Kisdb;

class KisdbHelper extends Kisdb
{
    /**
     * @return boolean
     */
    public function isConnected()
    {
        return $this->isConnected;
    }
}
