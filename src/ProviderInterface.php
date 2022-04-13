<?php

declare(strict_types=1);

namespace Whirlwind\Cache;

use Psr\SimpleCache\CacheInterface;

interface ProviderInterface extends CacheInterface
{
    public function add($key, $value, $ttl = null);

    public function increment($key);
}
