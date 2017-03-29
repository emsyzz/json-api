<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\IdentifierHandler;

/**
 * Identifier handler with rigidly set parameters for any objects
 *
 * @package Mikemirten\Component\JsonApi\ObjectTransformer\IdentifierHandler
 */
class RigidIdentifierHandler implements IdentifierHandlerInterface
{
    /**
     * Getter-method
     *
     * @var string
     */
    protected $getter;

    /**
     * Setter-method
     *
     * @var string
     */
    protected $setter;

    /**
     * RigidIdentifierHandler constructor.
     *
     * @param string $getter
     * @param string $setter Optional. Should not be set if "setIdentifier" have to be ignored
     */
    public function __construct(string $getter, string $setter = null)
    {
        $this->getter = $getter;
        $this->setter = $setter;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier($object): string
    {
        return (string) $object->{$this->getter}();
    }

    /**
     * {@inheritdoc}
     */
    public function setIdentifier($object, string $identifier)
    {
        if ($this->setter === null) {
            return;
        }

        $object->{$this->setter}($identifier);
    }
}