<?php

namespace Mikemirten\Component\JsonApi\Mapper\Definition;

use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Definition
 */
class LinkTest extends TestCase
{
    public function testBasics()
    {
        $link = new Link('name', 'repository', 'link');

        $this->assertSame('name', $link->getName());
        $this->assertSame('repository', $link->getRepositoryName());
        $this->assertSame('link', $link->getLinkName());
    }

    public function testParameters()
    {
        $link = new Link('name', 'repository', 'link');
        $link->setParameters(['test' => '123']);

        $this->assertSame(['test' => '123'], $link->getParameters());
    }

    public function testMetadata()
    {
        $link = new Link('name', 'repository', 'link');
        $link->setMetadata(['test' => '123']);

        $this->assertSame(['test' => '123'], $link->getMetadata());
    }

    public function testMergeBasics()
    {
        $extraLink = $this->createMock(Link::class);

        $extraLink->expects($this->once())
            ->method('getLinkName')
            ->willReturn('link2');

        $extraLink->expects($this->once())
            ->method('getRepositoryName')
            ->willReturn('repository2');

        $link = new Link('name', 'repository', 'link');
        $link->merge($extraLink);

        $this->assertSame('link2', $link->getLinkName());
        $this->assertSame('repository2', $link->getRepositoryName());
    }

    public function testMergeParameters()
    {
        $extraLink = $this->createMock(Link::class);

        $extraLink->expects($this->once())
            ->method('getParameters')
            ->willReturn([
                'parameter'  => 'asdfgh',
                'parameter2' => 'zxcvbn'
            ]);

        $link = new Link('name', 'repository', 'link');
        $link->setParameters(['parameter' => 'qwerty']);
        $link->merge($extraLink);

        $this->assertSame([
            'parameter'  => 'asdfgh',
            'parameter2' => 'zxcvbn'
        ], $link->getParameters());
    }

    public function testMergeMetadata()
    {
        $extraLink = $this->createMock(Link::class);

        $extraLink->expects($this->once())
            ->method('getMetadata')
            ->willReturn([
                'meta'  => 'asdfgh',
                'meta2' => 'zxcvbn'
            ]);

        $link = new Link('name', 'repository', 'link');
        $link->setMetadata(['meta' => 'qwerty']);
        $link->merge($extraLink);

        $this->assertSame([
            'meta'  => 'asdfgh',
            'meta2' => 'zxcvbn'
        ], $link->getMetadata());
    }
}