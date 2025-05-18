<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache;

use Laminas\Cache\Psr\SimpleCache\SimpleCacheDecorator;
use Laminas\Cache\Storage\Adapter\Memory;
use Laminas\Cache\Storage\StorageInterface;
use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;

class CacheManager implements CacheManagerInterface
{
    /** @var array<string, SimpleCacheInterface> */
    private array $caches = [];

    public function __construct(
        private array $namespaces,
        private AdapterFactory $adapterFactory
    ) {
    }

    public function getNamespace(string $namespace = 'default'): SimpleCacheInterface
    {
        if (isset($this->caches[$namespace])) {
            return $this->caches[$namespace];
        }

        if (! isset($this->namespaces['default'])) {
            $this->namespaces['default'] = [
                'adapter' => Memory::class,
                'options' => [],
                'ttl'     => 3600,
            ];
        }

        $base = $this->namespaces['default'];
        $override = $namespace !== 'default' ? $this->namespaces[$namespace] ?? [] : [];
        $config = array_replace_recursive($base, $override);

        $adapter = $config['adapter'];
        $options = $config['options'] ?? [];
        $ttl     = $config['ttl'] ?? 3600;

        /** @var StorageInterface $storage */
        $storage = $this->adapterFactory->create($adapter, $options);
        $storage->setOptions(['ttl' => $ttl]);

        $psr16 = new SimpleCacheDecorator($storage);
        return $this->caches[$namespace] = $psr16;
    }
    public function getAllLoadedNamespaces(): array
    {
        return $this->caches;
    }

    public function getAllConfiguredNamespaceKeys(): array
    {
        return array_keys($this->namespaces);
    }

    public function getAllConfiguredNamespaces(): array
    {
        return $this->namespaces;
    }

    public function clearNamespace(string $namespace): void
    {
        if (! isset($this->namespaces[$namespace]) && $namespace !== 'default') {
            throw new \InvalidArgumentException("Unknown namespace: $namespace");
        }
        $this->get($namespace)->clear();
    }

    public function clearAllConfiguredNamespaces(): void
    {
        $caches = $this->getAllConfigured();
        foreach ($caches as $namespace) {
            $cache = $this->get($namespace);
            $cache->clear();
        }
    }
}
