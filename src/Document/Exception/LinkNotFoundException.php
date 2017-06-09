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
     * Name of not found link
     *
     * @var string
     */
    protected $name;

    /**
     * Container inside of which a link has not been found
     *
     * @var LinksAwareInterface
     */
    protected $container;

    /**
     * LinkNotFoundException constructor.
     *
     * @param LinksAwareInterface $container
     * @param string              $name
     * @param \Exception | null   $previous
     */
    public function __construct(LinksAwareInterface $container, string $name, \Exception $previous = null)
    {
        $this->name      = $name;
        $this->container = $container;

        $info = method_exists($container, '__toString')
            ? (string) $container
            : get_class($container);

        $message = sprintf('Link "%s" not found inside of [%s].', $name, $info);

        parent::__construct($message, 0, $previous);
    }

    /**
     * Get name of not found link
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get container inside of which a link has not been found
     *
     * @return LinksAwareInterface
     */
    public function getContainer(): LinksAwareInterface
    {
        return $this->container;
    }
}