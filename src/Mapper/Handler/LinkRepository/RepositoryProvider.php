<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository;

/**
 * Provider of link repositories
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository
 */
class RepositoryProvider
{
    /**
     * Repositories
     *
     * @var RepositoryInterface[]
     */
    protected $repositories;

    /**
     * Register links' repository
     *
     * @param string              $name
     * @param RepositoryInterface $repository
     */
    public function registerRepository(string $name, RepositoryInterface $repository)
    {
        if (isset($this->repositories[$name])) {
            throw new \LogicException(sprintf('Links\' Repository "%s" is already registered.', $name));
        }

        $this->repositories[$name] = $repository;
    }

    /**
     * Get links' repository by name
     *
     * @param  string $name
     * @return RepositoryInterface
     */
    public function getRepository(string $name): RepositoryInterface
    {
        if (isset($this->repositories[$name])) {
            return $this->repositories[$name];
        }

        throw new \LogicException(sprintf('Unknown repository "%s"', $name));
    }
}