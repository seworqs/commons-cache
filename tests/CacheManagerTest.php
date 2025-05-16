<?php

declare(strict_types=1);

namespace Seworqs\Commons\Cache\Test;

use Laminas\Cache\Storage\Adapter\Memory;
use PHPUnit\Framework\TestCase;
use Seworqs\Commons\Cache\CacheManagerFactory;
use Psr\SimpleCache\CacheInterface;

final class CacheManagerTest extends TestCase
{
    public function testItProvidesDefaultMemoryCache(): void
    {
        $manager = CacheManagerFactory::createStandAlone([]);
        $cache = $manager->get();

        $this->assertInstanceOf(CacheInterface::class, $cache);

        $cache->set('foo', 'bar');
        $this->assertSame('bar', $cache->get('foo'));
    }

    public function testSeparateNamespacesAreIsolated(): void
    {
        $config = [
            'namespaces' => [
                'default' => [
                    'adapter' => Memory::class,
                    'options' => [],
                    'ttl'     => 3600,
                ],
                'second' => [
                    'adapter' => Memory::class,
                    'options' => [],
                    'ttl'     => 3600,
                ],
            ],
        ];

        $manager = CacheManagerFactory::createStandAlone($config);

        $default = $manager->get(); // default
        $second = $manager->get('second');

        $default->set('sharedKey', 'foo');
        $second->set('sharedKey', 'bar');

        $this->assertSame('foo', $default->get('sharedKey'));
        $this->assertSame('bar', $second->get('sharedKey'));
    }

    public function testTtlCanBeSet(): void
    {
        $config = [
            'namespaces' => [
                'default' => [
                    'adapter' => Memory::class,
                    'options' => [],
                    'ttl'     => 1, // 1 second
                ],
            ],
        ];

        $manager = CacheManagerFactory::createStandAlone($config);
        $cache = $manager->get();

        $cache->set('temp', 'value');
        $this->assertSame('value', $cache->get('temp'));

        sleep(2);
        $this->assertNull($cache->get('temp')); // expired
    }
}
