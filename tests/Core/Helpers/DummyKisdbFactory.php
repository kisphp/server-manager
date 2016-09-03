<?php

namespace tests\Core\Helpers;

use Kisphp\Core\DbFactory;

class DummyKisdbFactory extends DbFactory
{
    /**
     * @return \Kisphp\Kisdb
     */
    protected static function instantiateKisdb()
    {
        return KisdbHelper::getInstance();
    }
}
