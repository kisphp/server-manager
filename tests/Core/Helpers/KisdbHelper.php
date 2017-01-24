<?php

namespace tests\Core\Helpers;

use Kisphp\Kisdb;

class KisdbHelper extends Kisdb
{
    /**
     * @return bool
     */
    public function isConnected()
    {
        return $this->isConnected;
    }
}
