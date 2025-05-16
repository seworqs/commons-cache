<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache;

use Laminas\Cache\Psr\SimpleCache\SimpleCacheDecorator;
use Laminas\Cache\Storage\Adapter\Memory;
use Laminas\Cache\Storage\StorageInterface;
use Psr\SimpleCache\CacheInterface;

class CacheManager implements CacheManagerInterface
{
    /** @var array<string, CacheInterface> */
    private array $caches = [];

    public function __construct(
        private array $namespaces,
        private AdapterFactory $adapterFactory
    ) {}

    public function get(string $namespace = 'default'): CacheInterface
    {
        if (isset($this->caches[$namespace])) {
            return $this->caches[$namespace];
        }

        if (!isset($this->namespaces['default'])) {
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
}
