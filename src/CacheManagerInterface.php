<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache;

use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;

interface CacheManagerInterface
{
    public function getNamespace(string $namespace = 'default'): SimpleCacheInterface;

    /**
     * Returns all loaded namespace cache instances.
     *
     * @return array<string, CacheInterface>
     */
    public function getAllLoadedNamespaces(): array;

    public function getAllConfiguredNamespaceKeys(): array;

    public function getAllConfiguredNamespaces(): array;

    public function clearNamespace(string $namespace): void;

    public function clearAllConfiguredNamespaces(): void;
}
