<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

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
        $cache      = $this->createMock(CacheInterface::class);

        $cache->expects($this->at(0))
            ->method('get')
            ->with(CachedProvider::PREFIX . md5('Test'))
            ->willReturn(null);

        $cache->expects($this->at(1))
            ->method('set')
            ->with(
                CachedProvider::PREFIX . md5('Test'),
                $definition
            );

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
        $cache      = $this->createMock(CacheInterface::class);

        $cache->expects($this->once())
            ->method('get')
            ->with(CachedProvider::PREFIX . md5('Test'))
            ->willReturn(null);

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
        $cache      = $this->createMock(CacheInterface::class);

        $cache->expects($this->once())
            ->method('get')
            ->with(CachedProvider::PREFIX . md5('Test'))
            ->willReturn($definition);

        $cache->expects($this->never())
            ->method('set');

        $provider->expects($this->never())
            ->method('getDefinition');

        $cachedProvider = new CachedProvider($provider, $cache);

        $result = $cachedProvider->getDefinition('Test');

        $this->assertInstanceOf(Definition::class, $result);
    }
}