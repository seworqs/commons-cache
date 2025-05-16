<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache;

use Psr\SimpleCache\CacheInterface;

interface CacheManagerInterface
{
    public function get(string $namespace = 'default'): CacheInterface;
}
