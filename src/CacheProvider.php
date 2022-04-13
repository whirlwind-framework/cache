<?php

declare(strict_types=1);

namespace Whirlwind\Cache;

use Whirlwind\Cache\Storage\NotFoundException;
use Whirlwind\Cache\Storage\StorageInterface;

class CacheProvider implements ProviderInterface
{
    protected StorageInterface $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function get($key, $default = null)
    {
        try {
            return $this->storage->get($key);
        } catch (NotFoundException $e) {
            return $default;
        }
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->storage->set($key, $value, $ttl);
    }

    public function delete($key)
    {
        return $this->storage->delete($key);
    }

    public function clear()
    {
        return $this->storage->clear();
    }

    public function getMultiple($keys, $default = null)
    {
        return $this->storage->getMultiple($keys, $default);
    }

    public function setMultiple($values, $ttl = null)
    {
        $result = true;
        foreach ($values as $key => $value) {
            $set = $this->set($key, $value, $ttl);
            if (!$set) {
                $result = false;
            }
        }
        return $result;
    }

    public function deleteMultiple($keys)
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    public function has($key)
    {
        return $this->storage->has($key);
    }

    public function add($key, $value, $ttl = null)
    {
        return $this->storage->add($key, $value, $ttl);
    }

    public function increment($key)
    {
        return $this->storage->increment($key);
    }
}
