<?php

declare(strict_types=1);

namespace Whirlwind\Cache\Storage;

use Memcache;

class MemcacheStorage implements StorageInterface
{
    protected $memcache;

    public function __construct(array $servers = [])
    {
        $this->memcache = new Memcache();
        if (empty($servers)) {
            $servers = [[
                'host' => '127.0.0.1',
                'port' => 11211,
                'persistent' => true,
                'weight' => 1,
            ]];
        }
        foreach ($servers as $server) {
            if (isset($server['host'], $server['port'], $server['weight'])) {
                $this->memcache->addServer(
                    $server['host'],
                    $server['port'],
                    (isset($server['persistent']) ? $server['persistent'] : true),
                    $server['weight']
                );
            }
        }
    }

    public function get($key)
    {
        $data = $this->memcache->get($key);
        if (false === $data) {
            throw new NotFoundException("Cached value for $key not found");
        }
        return $data;
    }

    public function getMultiple($keys, $default = null)
    {
        return $this->memcache->get($keys);
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->memcache->set($key, $value, 0, $ttl);
    }

    public function delete($key)
    {
        $this->memcache->delete($key);
    }

    public function clear()
    {
        $this->memcache->flush();
    }

    public function has($key)
    {
        $value = $this->memcache->get($key);
        return $value !== false;
    }

    public function add($key, $value, $ttl = null)
    {
        return $this->memcache->add($key, $value, 0, $ttl);
    }

    public function increment($key)
    {
        return $this->memcache->increment($key);
    }
}
