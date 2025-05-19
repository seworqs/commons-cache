<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache;

use Psr\Container\ContainerInterface;

/**
 * Factory to create a CacheManager instance, either stand-alone or within Laminas.
 */
class CacheManagerFactory
{
    /**
     * Laminas integration: create a CacheManager using the ServiceManager config.
     *
     * Expected config location: $container->get('config')['cache']
     */
    public function __invoke(ContainerInterface $container): CacheManagerInterface
    {
        $config = $container->get('config')['cache'] ?? [];
        return self::create($config);
    }

    /**
     * Stand-alone creation without Laminas dependencies.
     *
     * @param array $config {
     *     @var array<string, array> $namespaces Namespaced adapter configurations. If no 'default' is provided,
     *                                            an in-memory array adapter will be used.
     * }
     *
     * @return CacheManagerInterface
     */
    public static function create(array $config): CacheManagerInterface
    {
        $namespaceConfigs = $config['namespaces'] ?? [];

        // Use default 'array' adapter with 300s TTL if not explicitly configured
        $defaultConfig = $namespaceConfigs['default'] ?? [
            'adapter' => 'array',
            'ttl' => 300,
        ];

        // Inject the default config into the namespace list if not already defined
        if (!isset($namespaceConfigs['default'])) {
            $namespaceConfigs['default'] = $defaultConfig;
        }

        // Create PSR-16 cache interface for the default namespace
        $defaultAdapter = AdapterFactory::createSimpleCacheInterface($defaultConfig);

        return new CacheManager($defaultAdapter, $namespaceConfigs);
    }
}
