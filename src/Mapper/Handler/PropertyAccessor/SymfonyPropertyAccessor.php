<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\PropertyAccessor;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface as SymfonyPropertyAccessorInterface;

/**
 * Symfony component based property accessor
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\PropertyAccessor
 */
class SymfonyPropertyAccessor implements PropertyAccessorInterface
{
    protected $accessor;

    /**
     * SymfonyPropertyAccessor constructor.
     *
     * @param SymfonyPropertyAccessorInterface $accessor
     */
    public function __construct(SymfonyPropertyAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($resource, string $path)
    {
        return $this->accessor->getValue($resource, $path);
    }
}