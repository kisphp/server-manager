<?php

namespace tests\Helpers;

use Symfony\Component\Filesystem\Filesystem as BaseFileSystem;

class FileSystem extends BaseFilesystem
{
    protected $cmds = [];

    public function copy($originFile, $targetFile, $overwriteNewerFiles = false)
    {
        dump(__METHOD__);
    }

    public function mkdir($dirs, $mode = 0777)
    {
        dump(__METHOD__);
    }

    public function exists($files)
    {
        dump(__METHOD__);
    }

    public function touch($files, $time = null, $atime = null)
    {
        dump(__METHOD__);
    }

    public function remove($files)
    {
        dump(__METHOD__);
    }

    public function chmod($files, $mode, $umask = 0000, $recursive = false)
    {
        dump(__METHOD__);
    }

    public function chown($files, $user, $recursive = false)
    {
        dump(__METHOD__);
    }

    public function chgrp($files, $group, $recursive = false)
    {
        dump(__METHOD__);
    }

    public function rename($origin, $target, $overwrite = false)
    {
        dump(__METHOD__);
    }

    public function symlink($originDir, $targetDir, $copyOnWindows = false)
    {
        $this->cmds[] = [
            'method' => __METHOD__,
            'filename' => $targetDir,
        ];
    }

    public function hardlink($originFile, $targetFiles)
    {
        dump(__METHOD__);
    }

    public function readlink($path, $canonicalize = false)
    {
        dump(__METHOD__);
    }

    public function makePathRelative($endPath, $startPath)
    {
        dump(__METHOD__);
    }

    public function mirror($originDir, $targetDir, \Traversable $iterator = null, $options = [])
    {
        dump(__METHOD__);
    }

    public function isAbsolutePath($file)
    {
        dump(__METHOD__);
    }

    public function tempnam($dir, $prefix)
    {
        dump(__METHOD__);
    }

    public function dumpFile($filename, $content)
    {
        $this->cmds[] = [
            'method' => __METHOD__,
            'filename' => $filename,
        ];
    }

    public function appendToFile($filename, $content)
    {
        $this->cmds[] = [
            'method' => __METHOD__,
            'filename' => $filename,
        ];
    }
}
