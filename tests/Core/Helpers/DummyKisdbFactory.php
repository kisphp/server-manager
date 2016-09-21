<?php

namespace tests\Core\Helpers;

use Kisphp\Core\AbstractFactory;

class DummyKisdbFactory extends AbstractFactory
{
    /**
     * @return \Kisphp\Kisdb
     */
    protected static function instantiateKisdb()
    {
        return KisdbHelper::getInstance();
    }
}
