<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Hydrator\Extension;

use Mikemirten\Component\JsonApi\Exception\InvalidDocumentException;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;

/**
 * Interface of hydrator's extension
 *
 * @package Mikemirten\Component\JsonApi\Hydrator\Extension
 */
interface ExtensionInterface
{
    /**
     * Get supported sections
     *
     * @return array Names of sections: ["meta", "data", ...]
     */
    public function supports(): array;

    /**
     * Hydrate object
     *
     * @param  mixed            $object   An object to hydrate
     * @param  mixed            $source   A source of data
     * @param  DocumentHydrator $hydrator
     * @throws InvalidDocumentException
     */
    public function hydrate($object, $source, DocumentHydrator $hydrator);
}