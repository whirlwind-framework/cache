<?php

declare(strict_types=1);

namespace Whirlwind\Cache\Storage;

interface StorageInterface
{
    /**
     * @param $key
     * @return mixed
     * @throws NotFoundException
     */
    public function get($key);

    public function getMultiple($keys, $default = null);

    public function set($key, $value, $ttl = null);

    public function delete($key);

    public function clear();

    public function has($key);

    public function add($key, $value, $ttl = null);

    public function increment($key);
}
