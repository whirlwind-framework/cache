<?php

declare(strict_types=1);

namespace Whirlwind\Cache\Storage;

use Memcached;

class MemcachedStorage implements StorageInterface
{
    protected $memcached;

    public function __construct(string $persistentId = '', array $servers = [])
    {
        $this->memcached = new Memcached($persistentId);
        if (empty($servers)) {
            $servers = [[
                'host' => '127.0.0.1',
                'port' => 11211,
                'weight' => 0
            ]];
        }
        foreach ($servers as $server) {
            if (isset($server['host'], $server['port'], $server['weight'])) {
                $this->memcached->addServer(
                    $server['host'],
                    $server['port'],
                    $server['weight']
                );
            }
        }
    }

    public function get($key)
    {
        $data = $this->memcached->get($key);
        if (false === $data) {
            throw new NotFoundException("Cached value for $key not found");
        }
        return $data;
    }

    public function getMultiple($keys, $default = null)
    {
        $result = [];
        foreach ($keys as $key) {
            $data = $this->memcached->get($key);
            if (false === $data) {
                $data = $default;
            }
            $result[$key] = $data;
        }
        return $result;
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->memcached->set($key, $value, $ttl);
    }

    public function delete($key)
    {
        $this->memcached->delete($key);
    }

    public function clear()
    {
        $this->memcached->flush();
    }

    public function has($key)
    {
        $this->memcached->get($key);
        return Memcached::RES_NOTFOUND !== $this->memcached->getResultCode();
    }

    public function add($key, $value, $ttl = null)
    {
        return $this->memcached->add($key, $value, $ttl);
    }

    public function increment($key)
    {
        return $this->memcached->increment($key);
    }
}
