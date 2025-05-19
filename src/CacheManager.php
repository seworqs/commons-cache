<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache;

use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;

/**
 * CacheManager providing PSR-16 caches per namespace using Symfony adapters.
 */
class CacheManager implements CacheManagerInterface
{
    protected SimpleCacheInterface $defaultAdapter;

    /** @var array<string, array> */
    protected array $configuredNamespaces;

    /** @var array<string, SimpleCacheInterface> */
    protected array $instances = [];

    /**
     * @param SimpleCacheInterface $defaultAdapter Default fallback adapter if a namespace has no specific config.
     * @param array<string, array> $configuredNamespaces Adapter config per namespace (optional).
     */
    public function __construct(SimpleCacheInterface $defaultAdapter, array $configuredNamespaces = [])
    {
        $this->defaultAdapter = $defaultAdapter;
        $this->configuredNamespaces = $configuredNamespaces;
    }

    /**
     * Get or create a PSR-16 cache instance for a given namespace.
     *
     * @param string $namespace
     * @return SimpleCacheInterface
     */
    public function getNamespace(string $namespace = 'default'): SimpleCacheInterface
    {
        if (isset($this->instances[$namespace])) {
            return $this->instances[$namespace];
        }

        $config = $this->configuredNamespaces[$namespace] ?? [];
        $config['namespace'] = $namespace;

        $adapter = isset($config['adapter'])
            ? AdapterFactory::createSimpleCacheInterface($config)
            : $this->defaultAdapter;

        $this->instances[$namespace] = $adapter;
        return $adapter;
    }

    /**
     * @return array<string, SimpleCacheInterface>
     */
    public function getAllLoadedNamespaces(): array
    {
        return $this->instances;
    }

    /**
     * @return string[]
     */
    public function getAllConfiguredNamespaceKeys(): array
    {
        return array_keys($this->configuredNamespaces);
    }

    /**
     * @return array<string, array>
     */
    public function getAllConfiguredNamespaces(): array
    {
        return $this->configuredNamespaces;
    }

    /**
     * Clears the given namespace if it has been initialized.
     */
    public function clearNamespace(string $namespace): void
    {
        $this->getNamespace($namespace)->clear();
    }

    /**
     * Clears all configured namespaces.
     */
    public function clearAllConfiguredNamespaces(): void
    {
        foreach ($this->getAllConfiguredNamespaceKeys() as $ns) {
            $this->clearNamespace($ns);
        }
    }
}
