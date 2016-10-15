<?php

namespace Kisphp\Command\Books;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CollectCommand extends Command
{
    protected function configure()
    {
        $this->setName('books:collect')
            ->setDescription('Collect new books')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = new \DirectoryIterator(__DIR__ . '/../../../../../');

        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }
            if (preg_match('/pdf$/', $file->getBasename())) {
                $this->processPdf($file);
            }
            if (preg_match('/zip$/', $file->getBasename())) {
                dump($file->getBasename());
            }
        }
    }

    protected function processZip(\DirectoryIterator $iterator)
    {
        $filename = $iterator->getBasename();

//        $pieces
        dump($filename);
    }

    protected function processPdf(\DirectoryIterator $iterator)
    {
//        dump($iterator);die;
    }
}
