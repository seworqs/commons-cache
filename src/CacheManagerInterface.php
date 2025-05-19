<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache;

use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;

interface CacheManagerInterface
{
    /**
     * Returns a PSR-16 cache for a given namespace. Will be created lazily.
     */
    public function getNamespace(string $namespace = 'default'): SimpleCacheInterface;

    /**
     * Returns all currently loaded namespace cache instances.
     *
     * @return array<string, SimpleCacheInterface>
     */
    public function getAllLoadedNamespaces(): array;

    /**
     * Returns all configured namespace keys.
     *
     * @return string[]
     */
    public function getAllConfiguredNamespaceKeys(): array;

    /**
     * Returns all configured namespaces including adapter config.
     *
     * @return array<string, array>
     */
    public function getAllConfiguredNamespaces(): array;

    /**
     * Clears all keys in the given namespace (if loaded).
     */
    public function clearNamespace(string $namespace): void;

    /**
     * Clears all configured namespaces (if loaded).
     */
    public function clearAllConfiguredNamespaces(): void;
}
