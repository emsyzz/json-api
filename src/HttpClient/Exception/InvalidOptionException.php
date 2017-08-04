<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\HttpClient\Exception;

/**
 * Class InvalidOptionException
 *
 * @package Mikemirten\Component\JsonApi\HttpClient\Exception
 */
class InvalidOptionException extends HttpClientException
{
    /**
     * InvalidOptionException constructor.
     *
     * @param string   $optionName
     * @param string[] $possibleOptions
     */
    public function __construct(string $optionName, array $possibleOptions)
    {
        parent::__construct(sprintf(
            'Option "%s" is not exists. Possible options to configure: "%s".',
            $optionName,
            implode('", "', $possibleOptions)
        ));
    }
}