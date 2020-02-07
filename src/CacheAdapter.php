<?php

namespace App;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

final class CacheAdapter extends FilesystemAdapter
{
    public function __construct(
        string $namespace = 'app'
    ) {
        $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'cache';
        if (!file_exists($path)) {
            mkdir($path);
        }
        parent::__construct($namespace, 3600, $path, null);
    }
}