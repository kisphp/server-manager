<?php

namespace Kisphp\Command;

use Kisphp\Core\AbstractFactory;
use Kisphp\Services\Server\Plugins\ApacheServer;
use Kisphp\Services\Server\Plugins\NginxServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Debug\Exception\ClassNotFoundException;
use tests\Helpers\FileSystem;

class TestCommand extends Command
{
    const SERVER_TYPE_APACHE2 = 'apache2';
    const SERVER_TYPE_NGINX = 'nginx';

    protected function configure()
    {
        $this->setName('test:server')
            ->addArgument('directory', InputArgument::REQUIRED)
            ->addArgument('public_directory', InputArgument::OPTIONAL, 'web')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $server = $this->createServer($input, $output);

        try {
            $server->createVhost('/home/server');
            $output->writeln($server->restartServer());
        } catch (\Exception $e) {

            $output->writeln($e->getMessage());
        }
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Kisphp\Services\Server\Plugins\ApacheServer|\Kisphp\Services\Server\Plugins\NginxServer
     * @throws \Symfony\Component\Debug\Exception\ClassNotFoundException
     */
    protected function createServer(InputInterface $input, OutputInterface $output)
    {
        dump($input);die;

        $fs = new FileSystem();
        $params = AbstractFactory::getParameters();

        if ($params['server_type'] === self::SERVER_TYPE_APACHE2) {
            return new ApacheServer($input, $output, $fs);
        }
        if ($params['server_type'] === self::SERVER_TYPE_NGINX) {
            return new NginxServer($input, $output, $fs);
        }

        throw new ClassNotFoundException('Server type ' . $params['server_type'] . ' does not exists', new \ErrorException());
    }

}
