<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache;

use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\NullAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;
use InvalidArgumentException;

/**
 * Factory for creating various Symfony cache adapters and interfaces.
 */
class AdapterFactory
{
    /**
     * Limited alias map to avoid unwanted dependencies.
     */
    private const ADAPTER_ALIAS_MAP = [
        'array'      => ArrayAdapter::class,
        'filesystem' => FilesystemAdapter::class,
        'null'       => NullAdapter::class,
    ];

    /**
     * Create a PSR-6 compatible cache pool.
     *
     * @param array $config {
     *     @var string|object|null $adapter Adapter alias, class, or instance
     *     @var string|null $namespace Optional prefix for supported adapters
     *     @var int|null $ttl Default TTL in seconds
     *     @var string|null $directory Filesystem cache location (for FilesystemAdapter)
     * }
     * @return CacheItemPoolInterface
     */
    public static function create(array $config = []): CacheItemPoolInterface
    {
        $adapterInput = $config['adapter'] ?? 'array';
        $namespace = $config['namespace'] ?? '';
        $ttl = $config['ttl'] ?? 3600;

        // 1. Adapter as object
        if ($adapterInput instanceof CacheItemPoolInterface) {
            return $adapterInput;
        }

        // 2. Adapter as alias or full class name
        $adapterClass = self::ADAPTER_ALIAS_MAP[$adapterInput] ?? $adapterInput;

        if (!class_exists($adapterClass)) {
            throw new InvalidArgumentException("Unknown cache adapter: $adapterInput");
        }

        // 3. Handle known constructors manually
        switch ($adapterClass) {
            case ArrayAdapter::class:
                return new ArrayAdapter($ttl, false);

            case FilesystemAdapter::class:
                $directory = $config['directory'] ?? sys_get_temp_dir() . '/cache';
                return new FilesystemAdapter($namespace, $ttl, $directory);

            case NullAdapter::class:
                return new NullAdapter();

            default:
                throw new InvalidArgumentException("Cannot instantiate adapter automatically: $adapterClass. Provide an instance instead.");
        }
    }

    /**
     * Create a PSR-16 (Simple Cache) interface.
     *
     * @param array $config
     * @return CacheInterface
     */
    public static function createSimpleCacheInterface(array $config = []): CacheInterface
    {
        return new Psr16Cache(self::create($config));
    }

    /**
     * Create a Symfony Contracts CacheInterface (lazy + atomic support).
     *
     * @param array $config
     * @return \Symfony\Contracts\Cache\CacheInterface
     */
    public static function createContractsCacheInterface(array $config = []): \Symfony\Contracts\Cache\CacheInterface
    {
        $adapter = self::create($config);

        if (!$adapter instanceof \Symfony\Contracts\Cache\CacheInterface) {
            throw new InvalidArgumentException('Adapter does not implement Symfony\Contracts\Cache\CacheInterface.');
        }

        return $adapter;
    }
}
