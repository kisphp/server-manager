<?php

namespace tests\Core;

use tests\Core\Helpers\DummyKisdbFactory;
use tests\Core\Helpers\KisdbHelper;

class DbFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function test_success_connection()
    {
        /** @var KisdbHelper $db */
        $db = DummyKisdbFactory::createDatabaseConnection();

        self::assertTrue($db->isConnected());
    }
}
