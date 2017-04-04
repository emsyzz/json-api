<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository;

use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository
 */
class RepositoryProviderTest extends TestCase
{
    public function testProvider()
    {
        $repository = $this->createMock(RepositoryInterface::class);

        $provider = new RepositoryProvider();
        $provider->registerRepository('test', $repository);

        $this->assertSame($repository, $provider->getRepository('test'));
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessageRegExp ~test_repository~
     */
    public function testOverride()
    {
        $repository = $this->createMock(RepositoryInterface::class);

        $provider = new RepositoryProvider();
        $provider->registerRepository('test_repository', $repository);
        $provider->registerRepository('test_repository', $repository);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessageRegExp ~test_repository~
     */
    public function testNotFound()
    {
        $provider = new RepositoryProvider();
        $provider->getRepository('test_repository');
    }
}