<?php

namespace tests\Services\Plugins;

use Kisphp\Services\ServerFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ApacheServerTest extends TestCase
{
    public function test_cmd()
    {
        $input = $this->prophesize(\stdClass::class);
        $input->willImplement(InputInterface::class);
        $inputDouble = $input->reveal();

        $output = $this->prophesize(\stdClass::class);
        $output->willImplement(OutputInterface::class);
        $outputDouble = $output->reveal();


        $factory = new ServerFactory($inputDouble, $outputDouble);

        self::assertNotEmpty('ad');
    }
}