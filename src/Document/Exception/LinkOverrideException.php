<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Exception;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;

/**
 * Class LinkOverrideException
 *
 * @package Mikemirten\Component\JsonApi\Document\Exception
 */
class LinkOverrideException extends DocumentException
{
    /**
     * LinkNotFoundException constructor.
     *
     * @param LinksAwareInterface $container
     * @param string              $name
     * @param \Exception | null   $previous
     */
    public function __construct(LinksAwareInterface $container, string $name, \Exception $previous = null)
    {
        $message = sprintf('Link "%s" already exists inside of [%s]. To set new one, the old one must be removed.', $name, $container);

        parent::__construct($message, 0, $previous);
    }
}