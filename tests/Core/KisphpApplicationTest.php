<?php

namespace tests\Core;

use Kisphp\Core\CommandsCollector;
use Kisphp\Core\KisphpApplication;
use Symfony\Component\Console\Application;
use tests\Core\Helpers\DummyCommand;

class KisphpApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function test_application()
    {
        $collector = new CommandsCollector();

        $kp = new KisphpApplication($collector);

        $kp->registerCommand(new DummyCommand());

        self::assertInstanceOf(Application::class, $kp->boot());
    }
}
