<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Definition\Exception;

class DefinitionNotFoundException extends DefinitionProviderException
{
    /**
     * Definition for class not found exception
     *
     * @param string     $class
     * @param \Throwable $previous
     */
    public function __construct(string $class, \Throwable $previous = null)
    {
        $message = sprintf('Definition for class "%s" not found.', $class);

        parent::__construct($message, 0, $previous);
    }
}