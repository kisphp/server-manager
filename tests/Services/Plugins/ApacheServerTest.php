<?php

namespace tests\Services\Plugins;

use Kisphp\Services\Server\Plugins\ApacheServer;
use Kisphp\Services\Server\Plugins\NginxServer;
use PHPUnit\Framework\TestCase;
use tests\Helpers\InputHelper;
use tests\Helpers\OutputHelper;
use tests\Helpers\FileSystem;

class ApacheServerTest extends TestCase
{
    const SERVER_PATH = '/home/server';

    public function test_create_vhost_apache()
    {
        $input = new InputHelper();
        $input
            ->setArgument('directory', 'app.local')
            ->setArgument('public_directory', 'web')
        ;

        $output = new OutputHelper();

        $server = $this->createApache2Server($input, $output);

        $server->createVhost(self::SERVER_PATH);

        $outputText = implode("\n", $output->getBuffer());

        self::assertContains('/etc/apache2/sites-available/app.local.conf', $outputText);
        self::assertContains('/etc/apache2/sites-enabled/app.local.conf', $outputText);

        /** @var FileSystem $fs */
        $fs = $server->getFs();
        $content = $fs->getFileContent();

        self::assertContains('DocumentRoot /home/server/app.local/web', $content);
        self::assertContains('CustomLog /home/server/app.local/access.log combined', $content);
        self::assertContains('ErrorLog /home/server/app.local/error.log', $content);
        self::assertContains('<Directory /home/server/app.local/web/>', $content);
        self::assertContains('ServerName app.local', $content);
        self::assertContains('ServerAlias www.app.local', $content);
    }

    public function test_create_vhost_nginx()
    {
        $input = new InputHelper();
        $input
            ->setArgument('directory', 'app.local')
            ->setArgument('public_directory', 'web')
        ;

        $output = new OutputHelper();

        $server = $this->createNginxServer($input, $output);

        $server->createVhost(self::SERVER_PATH);

        $outputText = implode("\n", $output->getBuffer());

        self::assertContains('/etc/nginx/sites-available/app.local', $outputText);
        self::assertContains('/etc/nginx/sites-enabled/app.local', $outputText);

        /** @var FileSystem $fs */
        $fs = $server->getFs();

        $content = $fs->getFileContent();

        self::assertContains('root /home/server/app.local/web;', $content);
        self::assertContains('server_name www.app.local app.local;', $content);
        self::assertContains('root /home/server/app.local/web;', $content);
        self::assertContains('error_log /home/server/app.local/nginx-error.log;', $content);
        self::assertContains('access_log /home/server/app.local/nginx-access.log;', $content);
    }

    /**
     * @param $input
     * @param $output
     *
     * @return \Kisphp\Services\Server\Plugins\ApacheServer
     */
    protected function createApache2Server($input, $output)
    {
        return new ApacheServer($input, $output, new FileSystem());
    }

    /**
     * @param $input
     * @param $output
     *
     * @return \Kisphp\Services\Server\Plugins\NginxServer
     */
    protected function createNginxServer($input, $output)
    {
        return new NginxServer($input, $output, new FileSystem());
    }
}
