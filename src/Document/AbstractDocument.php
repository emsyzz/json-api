<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataContainer;

/**
 * Abstract Document
 *
 * @see http://jsonapi.org/format/#document-structure
 *
 * Represents base JsonAPI-document structure
 * Supposed to be extended by case-based documents
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
abstract class AbstractDocument
{
    use MetadataContainer;
    use LinksContainer;
}