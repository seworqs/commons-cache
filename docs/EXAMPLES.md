# Examples

## Install via Composer

```bash
composer require seworqs/commons-cache
```

## Basic Usage

```php
use Seworqs\Commons\Cache\CacheManagerFactory;

/** Create the cache manager with optional namespace configurations */
$manager = CacheManagerFactory::createStandAlone([
    'namespaces' => [
        'default' => [
            'adapter' => 'memory',
            'options' => [],
            'ttl' => 3600,
        ],
        'menu' => [
            'adapter' => 'memory',
            'ttl' => 600,
        ],
    ],
]);

/** Get the default cache (PSR-16) */
$cache = $manager->get(); // or $manager->get('default')
$cache->set('foo', 'bar');
$value = $cache->get('foo'); // 'bar'
```

## Use cases
1. Multiple Cache Namespaces
   Each namespace can have:
    - Its own adapter (e.g. APCu, Filesystem, Redis)
    - Separate TTL
    - Individual options

        ```php
        $sessionCache = $manager->get('session');
        $menuCache = $manager->get('menu');

        $sessionCache->set('user_123', ['name' => 'Alice'], 900);  // override TTL
        $menuCache->set('main', ['Home', 'About'], 600);           // default TTL from config
        ```
2. Custom TTL per namespace

    ```php
    'namespaces' => [
        'short' => ['adapter' => 'memory', 'ttl' => 60],
        'long' =>  ['adapter' => 'memory', 'ttl' => 86400],
    ]
    ```

## Clear a specific namespace

```php
use Seworqs\Commons\Cache\CacheManagerFactory;

$manager = CacheManagerFactory::createStandAlone([
    'namespaces' => [
        'default' => ['adapter' => 'memory', 'ttl' => 3600],
        'menu'    => ['adapter' => 'memory', 'ttl' => 300],
    ],
]);

$manager->clear('menu');
```

## Clear all namespaces

```php
$manager->clearAll();
```

## List configured namespace keys

```php
$keys = $manager->getConfiguredNamespaceKeys(); // ['default', 'menu']
```

## Check which namespaces are active

```php
$active = array_keys($manager->getAllActive()); // e.g. ['default']
```

## Usage in Laminas Environment
Register the cache manager as a service using a factory:

```php
// In your module.config.php or service manager config
'service_manager' => [
    'factories' => [
        Seworqs\Commons\Cache\CacheManagerInterface::class => Seworqs\Commons\Cache\CacheManagerFactory::class,
    ],
],
```

Example configuration (e.g. in config/autoload/cache.global.php):

```php
return [
    'seworqs' => [
        'cache' => [
            'namespaces' => [
                'default' => [
                    'adapter' => Memory::class,
                    'options' => [],
                    'ttl' => 300,
                ],
                'translations' => [
                    'adapter' => FileSystem::class,
                    'options' => ['cache_dir' => 'data/cache/translations'],
                    'ttl' => 86400,
                ],
            ],
        ],
    ],
];
```

Retrieve in your service/controller:

```php
use Seworqs\Commons\Cache\CacheManagerInterface;

public function __construct(CacheManagerInterface $cacheManager)
{
    $this->cache = $cacheManager->get('translations');
}
```