<?php

namespace App\Factory;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

final class CacheAdapterFactory
{
    public static function createFSWithNamespace(string $namespace = 'app', ?string $directory = null): FilesystemAdapter
    {
        $path = $directory ?? dirname(__DIR__) . DIRECTORY_SEPARATOR . 'cache';
        if (!file_exists($path)) {
            mkdir($path);
        }

        return new FilesystemAdapter($namespace, 3600, $path);
    }
}