<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Psr\Cache\CacheItemPoolInterface;

/**
 * Caching decorator for a definition provider using PSR-6 cache.
 *
 * @see http://www.php-fig.org/psr/psr-6/
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class CachedProvider implements DefinitionProviderInterface
{
    const PREFIX = 'json_api_mapper.';

    /**
     * Delegated provider
     *
     * @var DefinitionProviderInterface
     */
    protected $provider;

    /**
     * PSR-6 Compatible cache
     *
     * @var CacheItemPoolInterface
     */
    protected $cache;

    /**
     * Locally cached definitions
     *
     * @var Definition[]
     */
    protected $definitions = [];

    /**
     * CachedProvider constructor.
     *
     * @param DefinitionProviderInterface $provider
     * @param CacheItemPoolInterface      $cache
     */
    public function __construct(DefinitionProviderInterface $provider, CacheItemPoolInterface $cache)
    {
        $this->provider = $provider;
        $this->cache    = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition(string $class): Definition
    {
        if (! isset($this->definitions[$class])) {
            $this->definitions[$class] = $this->provideDefinition($class);
        }

        return $this->definitions[$class];
    }

    /**
     * Provide definition: try cache or get from provider
     *
     * @param  string $class
     * @return Definition
     */
    public function provideDefinition(string $class): Definition
    {
        $key  = self::PREFIX . md5($class);
        $item = $this->cache->getItem($key);

        if ($item->isHit()) {
            return $item->get();
        }

        $definition = $this->provider->getDefinition($class);

        $item->set($definition);
        $this->cache->save($item);

        return $definition;
    }
}