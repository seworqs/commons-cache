<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache;

use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;

interface CacheManagerInterface
{
    public function get(string $namespace = 'default'): SimpleCacheInterface;
}
