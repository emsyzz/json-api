<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository;

/**
 * Interface of link's repository
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository
 */
interface RepositoryInterface
{
    /**
     * Get link by name.
     * Returned link contains a resolved http-reference and metadata.
     *
     * @param  string $name
     * @param  array  $parameters
     * @return mixed
     */
    public function getLink(string $name, array $parameters): Link;
}