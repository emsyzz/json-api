<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document\Exception;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;

/**
 * Class LinkNotFoundException
 *
 * @package Mikemirten\Component\JsonApi\Document\Exception
 */
class LinkNotFoundException extends DocumentException
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
        $message = sprintf('Link "%s" not found inside of [%s].', $name, $container);

        parent::__construct($message, 0, $previous);
    }
}