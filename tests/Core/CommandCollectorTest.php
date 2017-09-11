<?php

namespace tests\Core;

use Kisphp\Core\CommandsCollector;
use PHPUnit\Framework\TestCase;
use tests\Core\Helpers\DummyCommand;

class CommandCollectorTest extends TestCase
{
    public function test_CommandsCollector()
    {
        $cc = new CommandsCollector();

        $cc->addCommand(new DummyCommand());

        self::assertGreaterThan(1, count($cc->getCommands()));
    }
}
