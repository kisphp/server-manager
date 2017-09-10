<?php

namespace tests\Core;

use Kisphp\Core\CommandsCollector;
use Kisphp\Core\KisphpApplication;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use tests\Core\Helpers\DummyCommand;

class KisphpApplicationTest extends TestCase
{
    public function test_application()
    {
        $collector = new CommandsCollector();

        $kp = new KisphpApplication($collector);

        $kp->registerCommand(new DummyCommand());

        self::assertInstanceOf(Application::class, $kp->boot());
    }
}
