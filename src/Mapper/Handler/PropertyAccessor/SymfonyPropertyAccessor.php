<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\PropertyAccessor;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface as SymfonyPropertyAccessorInterface;

/**
 * A property-accessor implementation based on the Symfony PropertyAccess component.
 *
 * @see http://symfony.com/doc/current/components/property_access.html
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