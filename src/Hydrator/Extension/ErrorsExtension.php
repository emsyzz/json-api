<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Hydrator\Extension;

use Mikemirten\Component\JsonApi\Document\Behaviour\ErrorsAwareInterface;
use Mikemirten\Component\JsonApi\Document\ErrorObject;
use Mikemirten\Component\JsonApi\Exception\InvalidDocumentException;
use Mikemirten\Component\JsonApi\Hydrator\DocumentHydrator;

/**
 * "errors" object extension
 *
 * @see http://jsonapi.org/format/#errors
 *
 * @package Mikemirten\Component\JsonApi\Hydrator\Extension
 */
class ErrorsExtension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function hydrate($object, $source, DocumentHydrator $hydrator)
    {
        if (! $object instanceof ErrorsAwareInterface) {
            throw new InvalidDocumentException(sprintf(
                'Given instance of "%s" does not implements "%s"',
                get_class($object),
                ErrorsAwareInterface::class
            ));
        }

        foreach ($source as $content)
        {
            $error = $this->createError($content, $hydrator);

            $object->addError($error);
        }
    }

    /**
     * Create error
     *
     * @param  $source
     * @param  DocumentHydrator $hydrator
     * @return ErrorObject
     */
    protected function createError($source, DocumentHydrator $hydrator): ErrorObject
    {
        $error = new ErrorObject();

        foreach (['id', 'status', 'code', 'title', 'detail'] as $property)
        {
            if (isset($source->$property)) {
                $error->{'set' . ucfirst($property)}($source->$property);
            }
        }

        $hydrator->hydrateObject($error, $source);

        return $error;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(): array
    {
        return ['errors'];
    }
}