<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache;

use Laminas\Cache\Storage\Adapter\Memory;
use Psr\Container\ContainerInterface;
use Laminas\Cache\Service\StorageAdapterFactoryInterface;

class CacheManagerFactory
{
    public static function createStandAlone(array $config): CacheManagerInterface
    {
        if (empty($config['namespaces']['default'])) {
            $config['namespaces']['default'] = [
                'adapter' => Memory::class,
                'options' => [],
                'ttl'     => 3600,
            ];
        }

        return new CacheManager(
            $config['namespaces'],
            new AdapterFactory()
        );
    }

    public function __invoke(ContainerInterface $container): CacheManagerInterface
    {
        $config = $container->get('config')['seworqs']['cache'] ?? [];
        $namespaces = $config['namespaces'] ?? [];
        $adapterFactory = $container->get(StorageAdapterFactoryInterface::class);

        return new CacheManager($namespaces, $adapterFactory);
    }
}
