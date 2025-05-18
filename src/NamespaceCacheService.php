<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache\Service;

use Seworqs\Commons\Cache\CacheManagerInterface;
use Psr\SimpleCache\CacheInterface;

class NamespaceCacheService
{
    public function __construct(
        private readonly CacheManagerInterface $manager
    ) {}

    /**
     * Return all registered namespaces.
     */
    public function listNamespaces(): array
    {
        if (!method_exists($this->manager, 'getAllConfiguredNamespaceKeys')) {
            throw new \RuntimeException('Cache manager does not support namespace listing.');
        }

        return $this->manager->getAllConfiguredNamespaceKeys();
    }

    public function clearNamespace(string $namespace): bool
    {
        return $this->manager->get($namespace)->clear();
    }

    public function deleteKey(string $namespace, string $key): bool
    {
        return $this->manager->get($namespace)->delete($key);
    }

    public function getKey(string $namespace, string $key): mixed
    {
        return $this->manager->get($namespace)->get($key);
    }

    public function hasKey(string $namespace, string $key): bool
    {
        return $this->manager->get($namespace)->has($key);
    }
}
