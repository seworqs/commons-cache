# SEworqs Commons Cache 

A lightweight PSR-16 cache manager with multi-namespace support, configurable adapters, and TTL handling.

## Installation
```bash
composer require seworqs/commons-cache
```
## Usage
TODO
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
> The default namespace is automatically configured with the Memory adapter if not explicitly provided.

> [More examples](docs/Examples.md)

## Features

- [X] Multiple named namespaces (each with its own adapter, options, and TTL)
- [X] Merging of namespace-specific config with a shared default
- [X] Standalone usage or integration into Laminas via service factories
- [X] Adapters like memory, filesystem, APCu, Redis, and more — using Laminas Cache v4

> See our [examples](docs/Examples.md)

## License
Apache-2.0, see [LICENSE](./LICENSE)

## About SEworqs
Seworqs builds clean, reusable modules for PHP and Mendix developers.

Learn more at [github.com/seworqs](https://github.com/seworqs)

## Badges
[![Latest Version](https://img.shields.io/packagist/v/seworqs/commons-cache.svg?style=flat-square)](https://packagist.org/packages/seworqs/commons-cache)
[![Total Downloads](https://img.shields.io/packagist/dt/seworqs/commons-cache.svg?style=flat-square)](https://packagist.org/packages/seworqs/lcommons-cache)
[![License](https://img.shields.io/packagist/l/seworqs/commons-cache?style=flat-square)](https://packagist.org/packages/seworqs/commons-cache)
[![PHP Version](https://img.shields.io/packagist/php-v/seworqs/commons-cache.svg?style=flat-square)](https://packagist.org/packages/seworqs/commons-cache)
[![Made by SEworqs](https://img.shields.io/badge/made%20by-SEworqs-002d74?style=flat-square&logo=https://raw.githubusercontent.com/seworqs/commons-cache/main/assets/logo.svg&logoColor=white)](https://github.com/seworqs)