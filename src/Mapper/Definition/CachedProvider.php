<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use Psr\SimpleCache\CacheInterface;

/**
 * Caching decorator for a definition provider using PSR-16 compatible cache.
 *
 * @see http://www.php-fig.org/psr/psr-16/
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
     * PSR-16 Compatible cache
     *
     * @var CacheInterface
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
     * @param CacheInterface              $cache
     */
    public function __construct(DefinitionProviderInterface $provider, CacheInterface $cache)
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
        $data = $this->cache->get($key);

        if ($data !== null) {
            return unserialize($data);
        }

        $definition = $this->provider->getDefinition($class);

        $this->cache->set($key, serialize($definition));

        return $definition;
    }
}