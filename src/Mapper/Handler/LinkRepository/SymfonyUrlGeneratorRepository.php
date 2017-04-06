<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * A link-repository implementation based on the Symfony Router component.
 *
 * @see http://symfony.com/doc/current/routing.html
 *
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository
 */
class SymfonyUrlGeneratorRepository implements RepositoryInterface
{
    /**
     * Symfony url generator
     *
     * @var UrlGeneratorInterface
     */
    protected $generator;

    /**
     * SymfonyRouterRepository constructor.
     *
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * {@inheritdoc}
     */
    public function getLink(string $name, array $parameters): Link
    {
        $reference = $this->generator->generate($name, $parameters);

        return new Link($reference);
    }
}