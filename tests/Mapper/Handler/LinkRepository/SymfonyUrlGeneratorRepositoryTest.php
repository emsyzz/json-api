<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository
 */
class SymfonyUrlGeneratorRepositoryTest extends TestCase
{
    public function testGenerate()
    {
        $generator = $this->createMock(UrlGeneratorInterface::class);

        $generator->expects($this->once())
            ->method('generate')
            ->with(
                'test_route',
                ['test_param' => 'test_value']
            )
            ->willReturn('http://test_domain.com');

        $repository = new SymfonyUrlGeneratorRepository($generator);

        $link = $repository->getLink(
            'test_route',
            ['test_param' => 'test_value']
        );

        $this->assertInstanceOf(Link::class, $link);
        $this->assertSame('http://test_domain.com', $link->getReference());
    }
}