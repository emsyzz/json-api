<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class CachedProviderTest extends TestCase
{
    public function testGetDefinition()
    {
        $definition = $this->createMock(Definition::class);
        $provider   = $this->createMock(DefinitionProviderInterface::class);
        $cache      = $this->createMock(CacheItemPoolInterface::class);
        $cacheItem  = $this->createMock(CacheItemInterface::class);

        $cache->expects($this->at(0))
            ->method('getItem')
            ->with(CachedProvider::PREFIX . md5('Test'))
            ->willReturn($cacheItem);

        $cache->expects($this->at(1))
            ->method('save')
            ->with($cacheItem)
            ->willReturn(true);

        $cacheItem->expects($this->at(0))
            ->method('isHit')
            ->willReturn(false);

        $cacheItem->expects($this->at(1))
            ->method('set')
            ->with($definition);

        $provider->expects($this->once())
            ->method('getDefinition')
            ->with('Test')
            ->willReturn($definition);

        $cachedProvider = new CachedProvider($provider, $cache);

        $result = $cachedProvider->getDefinition('Test');

        $this->assertSame($definition, $result);
    }

    /**
     * Test of internal cache
     */
    public function testGetDefinitionTwice()
    {
        $definition = $this->createMock(Definition::class);
        $provider   = $this->createMock(DefinitionProviderInterface::class);
        $cache      = $this->createMock(CacheItemPoolInterface::class);
        $cacheItem  = $this->createMock(CacheItemInterface::class);

        $cache->expects($this->once())
            ->method('getItem')
            ->with(CachedProvider::PREFIX . md5('Test'))
            ->willReturn($cacheItem);

        $cache->expects($this->once())
            ->method('save')
            ->with($cacheItem)
            ->willReturn(true);

        $cacheItem->expects($this->once())
            ->method('isHit')
            ->willReturn(false);

        $cacheItem->expects($this->once())
            ->method('set')
            ->with($definition);

        $provider->expects($this->once())
            ->method('getDefinition')
            ->with('Test')
            ->willReturn($definition);

        $cachedProvider = new CachedProvider($provider, $cache);

        $cachedProvider->getDefinition('Test');
        $cachedProvider->getDefinition('Test');
    }

    public function testGetCachedDefinition()
    {
        $definition = $this->createMock(Definition::class);
        $provider   = $this->createMock(DefinitionProviderInterface::class);
        $cache      = $this->createMock(CacheItemPoolInterface::class);
        $cacheItem  = $this->createMock(CacheItemInterface::class);

        $cache->expects($this->once())
            ->method('getItem')
            ->with(CachedProvider::PREFIX . md5('Test'))
            ->willReturn($cacheItem);

        $cache->expects($this->never())
            ->method('save');

        $cacheItem->expects($this->never())
            ->method('set');

        $cacheItem->expects($this->once())
            ->method('isHit')
            ->willReturn(true);

        $cacheItem->expects($this->once())
            ->method('get')
            ->willReturn($definition);

        $provider->expects($this->never())
            ->method('getDefinition');

        $cachedProvider = new CachedProvider($provider, $cache);

        $result = $cachedProvider->getDefinition('Test');

        $this->assertInstanceOf(Definition::class, $result);
    }
}