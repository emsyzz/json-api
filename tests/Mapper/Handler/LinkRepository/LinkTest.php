<?php

namespace Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository;

use PHPUnit\Framework\TestCase;

/**
 * @group   mapper
 * @package Mikemirten\Component\JsonApi\Mapper\Handler\LinkRepository
 */
class LinkTest extends TestCase
{
    public function testLink()
    {
        $link = new Link('http://test.com', ['test' => '123']);

        $this->assertSame('http://test.com', $link->getReference());
        $this->assertSame(['test' => '123'], $link->getMetadata());
    }
}