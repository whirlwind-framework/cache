<?php

declare(strict_types=1);

namespace Whirlwind\Cache\Storage;

class InMemoryStorage implements StorageInterface
{
    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function get($key)
    {
        if (!isset($this->data[$key])) {
            throw new NotFoundException("Cached value for $key not found");
        }
        return $this->data[$key];
    }

    public function set($key, $value, $ttl = null)
    {
        $this->data[$key] = $value;
    }

    public function delete($key)
    {
        unset($this->data[$key]);
    }

    public function clear()
    {
        $this->data = [];
    }

    public function add($key, $value, $ttl = null)
    {
        $this->data[$key] = $value;
    }

    public function increment($key)
    {
        if (!isset($this->data[$key])) {
            $this->data[$key] = 0;
        }
        $this->data[$key]++;
    }

    public function getMultiple($keys, $default = null)
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $default;
            if ($this->has($key)) {
                $result[$key] = $this->data[$key];
            }
        }
        return $result;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }
}
