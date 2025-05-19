<?php

declare(strict_types=1);

namespace SeworqsTest\Commons\Cache;

use PHPUnit\Framework\TestCase;
use Seworqs\Commons\Cache\CacheManagerFactory;
use Psr\SimpleCache\CacheInterface;

final class CacheManagerTest extends TestCase
{
    public function testDefaultNamespaceWorksWithoutExplicitConfig(): void
    {
        $manager = CacheManagerFactory::create([]);

        $cache = $manager->getNamespace(); // default
        $this->assertInstanceOf(CacheInterface::class, $cache);

        $cache->set('test_key', 'test_value');
        $this->assertSame('test_value', $cache->get('test_key'));
    }

    public function testPerNamespaceConfigurationCreatesSeparateInstances(): void
    {
        $manager = CacheManagerFactory::create([
            'namespaces' => [
                'a' => ['adapter' => 'array', 'ttl' => 100],
                'b' => ['adapter' => 'array', 'ttl' => 200],
            ],
        ]);

        $a = $manager->getNamespace('a');
        $b = $manager->getNamespace('b');

        $a->set('key', 'value_a');
        $b->set('key', 'value_b');

        $this->assertSame('value_a', $a->get('key'));
        $this->assertSame('value_b', $b->get('key'));
    }

    public function testDefaultFallbackWhenNoAdapterSpecified(): void
    {
        $manager = CacheManagerFactory::create([
            'namespaces' => [
                'default' => ['ttl' => 300], // no adapter specified
            ],
        ]);

        $cache = $manager->getNamespace();
        $this->assertInstanceOf(CacheInterface::class, $cache);

        $cache->set('fallback_key', 'fallback_value');
        $this->assertSame('fallback_value', $cache->get('fallback_key'));
    }

    public function testClearNamespaceOnlyAffectsThatNamespace(): void
    {
        $manager = CacheManagerFactory::create([
            'namespaces' => [
                'users' => ['adapter' => 'array'],
                'logs'  => ['adapter' => 'array'],
            ],
        ]);

        $users = $manager->getNamespace('users');
        $logs = $manager->getNamespace('logs');

        $users->set('uid_1', 'John');
        $logs->set('event_1', 'Logged in');

        $manager->clearNamespace('users');

        $this->assertNull($users->get('uid_1'));
        $this->assertSame('Logged in', $logs->get('event_1'));
    }

    public function testClearAllConfiguredNamespaces(): void
    {
        $manager = CacheManagerFactory::create([
            'namespaces' => [
                'a' => ['adapter' => 'array'],
                'b' => ['adapter' => 'array'],
            ],
        ]);

        $manager->getNamespace('a')->set('x', 123);
        $manager->getNamespace('b')->set('y', 456);

        $manager->clearAllConfiguredNamespaces();

        $this->assertNull($manager->getNamespace('a')->get('x'));
        $this->assertNull($manager->getNamespace('b')->get('y'));
    }
    public function testItemExpiresAfterTtl(): void
    {
        $manager = CacheManagerFactory::create([
            'namespaces' => [
                'short' => ['adapter' => 'array', 'ttl' => 1],
            ],
        ]);

        $cache = $manager->getNamespace('short');
        $cache->set('temp_key', 'short_lived');

        $this->assertSame('short_lived', $cache->get('temp_key'));

        sleep(2);

        $this->assertNull($cache->get('temp_key'), 'Cache item should be expired after TTL');
    }

    public function testManualClearRemovesCachedItem(): void
    {
        $manager = CacheManagerFactory::create([
            'namespaces' => [
                'clear_test' => ['adapter' => 'array'],
            ],
        ]);

        $cache = $manager->getNamespace('clear_test');

        $cache->set('key_to_clear', 'should_be_removed');
        $this->assertSame('should_be_removed', $cache->get('key_to_clear'));

        $cache->clear();

        $this->assertNull($cache->get('key_to_clear'), 'Cache item should be removed after manual clear');
    }

}
