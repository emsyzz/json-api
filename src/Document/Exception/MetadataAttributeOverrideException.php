<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Exception;

use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;

/**
 * Class MetadataAttributeOverrideException
 *
 * @package Mikemirten\Component\JsonApi\Document\Exception
 */
class MetadataAttributeOverrideException extends DocumentException
{
    /**
     * MetadataAttributeOverrideException constructor.
     *
     * @param MetadataAwareInterface $container
     * @param string                 $name
     * @param \Exception | null      $previous
     */
    public function __construct(MetadataAwareInterface $container, string $name, \Exception $previous = null)
    {
        $message = sprintf('Attribute "%s" of metadata already exists inside of [%s]. To set new one, the old one must be removed.', $name, $container);

        parent::__construct($message, 0, $previous);
    }
}