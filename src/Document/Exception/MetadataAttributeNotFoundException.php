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
     * MetadataAttributeNotFoundException constructor.
     *
     * @param MetadataAwareInterface $container
     * @param string                 $name
     * @param \Exception | null      $previous
     */
    public function __construct(MetadataAwareInterface $container, string $name, \Exception $previous = null)
    {
        $message = sprintf('Attribute "%s" of metadata not found inside of [%s].', $name, $container);

        parent::__construct($message, 0, $previous);
    }
}