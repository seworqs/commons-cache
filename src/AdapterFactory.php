<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache;

use Laminas\Cache\Storage\Adapter\Memory;
use Laminas\Cache\Storage\StorageInterface;
use Laminas\Cache\Storage\AdapterPluginManager;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\ServiceManager\ServiceManager;

class AdapterFactory
{
    private AdapterPluginManager $pluginManager;

    public function __construct()
    {
        $serviceManager = new ServiceManager([
            'factories' => [
                Memory::class => InvokableFactory::class,
            ],
            'aliases' => [
                'memory' => Memory::class,
            ],
        ]);

        $this->pluginManager = new AdapterPluginManager($serviceManager);
    }

    public function create(string $adapter, array $options = []): StorageInterface
    {
        return $this->pluginManager->get($adapter, $options);
    }
}
