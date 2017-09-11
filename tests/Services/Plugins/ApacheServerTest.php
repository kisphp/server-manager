<?php

namespace tests\Services\Plugins;

use Kisphp\Services\ServerFactory;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ApacheServerTest extends TestCase
{
    const SERVER_PATH = '/home/server';

    public function test_cmd()
    {
        $inputDouble = $this->prophesize(InputInterface::class);
        $inputDouble->getArgument('directory')->willReturn('app.dev');
        $inputDouble->getArgument('public_directory')->willReturn('web');
        $input = $inputDouble->reveal();

        $outputDouble = $this->prophesize(OutputInterface::class);
        $output = $outputDouble->reveal();

        $fsDouble = $this->prophesize(Filesystem::class);

        $fs = $fsDouble->reveal();

        $factory = new ServerFactory('apache2', $input, $output, $fs);

        $factory->getServer()->createVhost(self::SERVER_PATH);

        self::assertNotEmpty('ad');
    }
}
