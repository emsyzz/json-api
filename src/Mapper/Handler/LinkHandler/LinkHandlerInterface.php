<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\LinkHandler;

use Mikemirten\Component\JsonApi\Mapper\Definition\Behaviour\LinksAwareInterface as LinksAwareDefinitionInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface as LinksAwareDocumentInterface;

/**
 * Interface of links handler
 *
 * @package Mapper\Handler\LinkHandler
 */
interface LinkHandlerInterface
{
    /**
     * Handle links provided by definition.
     *
     * @param mixed                         $object
     * @param LinksAwareDefinitionInterface $definition
     * @param LinksAwareDocumentInterface   $document
     */
    public function handleLinks(
        $object,
        LinksAwareDefinitionInterface $definition,
        LinksAwareDocumentInterface   $document
    );
}