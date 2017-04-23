<?php

namespace tests\Core\Helpers;

use Kisphp\Core\AbstractFactory;
use Kisphp\Core\Kisdb;

class DummyKisdbFactory extends AbstractFactory
{
    /**
     * @return \Kisphp\Kisdb
     */
    protected static function instantiateKisdb()
    {
        return Kisdb::getInstance();
    }
}
