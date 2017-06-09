<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Exception;

use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;

/**
 * Class MetadataAttributeNotFoundException
 *
 * @package Mikemirten\Component\JsonApi\Document\Exception
 */
class MetadataAttributeNotFoundException extends DocumentException
{
    /**
     * Name of not found attribute of metadata
     *
     * @var string
     */
    protected $name;

    /**
     * Container inside of which an attribute of metadata has not been found
     *
     * @var MetadataAwareInterface
     */
    protected $container;

    /**
     * MetadataAttributeNotFoundException constructor.
     *
     * @param MetadataAwareInterface $container
     * @param string                 $name
     * @param \Exception | null      $previous
     */
    public function __construct(MetadataAwareInterface $container, string $name, \Exception $previous = null)
    {
        $this->name      = $name;
        $this->container = $container;

        $info = method_exists($container, '__toString')
            ? (string) $container
            : get_class($container);

        $message = sprintf('Attribute "%s" of metadata not found inside of [%s].', $name, $info);

        parent::__construct($message, 0, $previous);
    }

    /**
     * Get name of not found attribute of metadata
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get container inside of which an attribute of metadata has not been found
     *
     * @return MetadataAwareInterface
     */
    public function getContainer(): MetadataAwareInterface
    {
        return $this->container;
    }
}