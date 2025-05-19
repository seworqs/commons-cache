# SEworqs Commons Cache

A lightweight PSR-16 cache manager with multi-namespace support, configurable Symfony adapters, and TTL handling.  
Built on top of [symfony/cache](https://symfony.com/doc/current/components/cache.html), fully compatible with PHP 8.1+ and PSR standards.

---

## Installation

```bash
composer require seworqs/commons-cache
```

---

## Basic Usage (stand-alone)

```php
use Seworqs\Commons\Cache\CacheManagerFactory;

$config = [
    'namespaces' => [
        'default' => [
            'adapter' => 'array',
            'ttl' => 3600,
        ],
        'menu' => [
            'adapter' => 'filesystem',
            'ttl' => 600,
            'directory' => __DIR__ . '/cache/menu',
        ],
    ],
];

$manager = CacheManagerFactory::create($config);

$cache = $manager->getNamespace();
$cache->set('foo', 'bar');
$value = $cache->get('foo');
```

---

## Adapter Options

You may use:

### ✅ Aliases:
- `'array'`
- `'filesystem'`
- `'null'`

### ✅ Full class names:
```php
'adapter' => Symfony\Component\Cache\Adapter\PhpFilesAdapter::class
```

### ✅ Ready-to-use instances:
```php
'adapter' => new Symfony\Component\Cache\Adapter\RedisAdapter($redisClient, 'namespace', 600)
```

Other adapters like Redis, Memcached, or PDO can be used by providing their full class name and handling construction yourself.

---

## Laminas Integration

```php
'factories' => [
    Seworqs\Commons\Cache\CacheManagerInterface::class => Seworqs\Commons\Cache\CacheManagerFactory::class,
],
```

Use via dependency injection:
```php
public function __construct(private CacheManagerInterface $cacheManager) {}
```

---

## Testing

```bash
composer test
```

Runs PHPUnit using in-memory adapters.

---

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
