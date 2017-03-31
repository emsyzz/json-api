<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

/**
 * Interface of a link
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
interface LinkInterface
{
    /**
     * Get http-reference
     *
     * @return string
     */
    public function getReference(): string;
}