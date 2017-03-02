<?php
declare(strict_types = 1);

namespace Mikemirten\JsonApi\Component\Document;

use Mikemirten\JsonApi\Component\Document\Behaviour\LinksContainer;
use Mikemirten\JsonApi\Component\Document\Behaviour\MetadataContainer;

/**
 * Abstract Document
 *
 * @see http://jsonapi.org/format/#document-structure
 *
 * Represents base JsonAPI-document structure
 * Supposed to be extended by case-based documents
 *
 * @package Mikemirten\JsonApi\Component\Document
 */
abstract class AbstractDocument
{
    use MetadataContainer;
    use LinksContainer;
}