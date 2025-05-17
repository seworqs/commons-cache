<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache;

interface CacheInterface
{
    public function get(string $key, mixed $default = null): mixed;
    public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool;
    public function delete(string $key): bool;
    public function has(string $key): bool;
    public function clear(): bool;
}