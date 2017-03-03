<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\LinksContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataContainer;

/**
 * Abstract relationship
 *
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 *
 * Represents base relationship structure
 * Supposed to be extended by case based relationships
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
abstract class AbstractRelationship implements MetadataAwareInterface, LinksAwareInterface
{
    use MetadataContainer;
    use LinksContainer;
}