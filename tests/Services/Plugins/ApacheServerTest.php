<?php

namespace tests\Services\Plugins;

use Kisphp\Services\Server\Plugins\ApacheServer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use tests\Helpers\OutputHelper;
use tests\Helpers\FileSystem;

class ApacheServerTest extends TestCase
{
    const SERVER_PATH = '/home/server';

    public function test_cmd()
    {
        $input = new ArrayInput([
            'directory' => 'app.local',
            'public_directory' => 'web',
        ]);

        $output = new OutputHelper();

        $server = new ApacheServer($input, $output, new FileSystem());

        $server->createVhost(self::SERVER_PATH);
    }
}
