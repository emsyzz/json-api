<?php

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group document
 */
class ErrorObjectTest extends TestCase
{
    /**
     * @var ErrorObject
     */
    protected $error;

    public function setUp()
    {
        $this->error = new ErrorObject();
    }

    public function testId()
    {
        $this->error->setId('123');

        $this->assertSame('123', $this->error->getId());
    }

    public function testStatus()
    {
        $this->error->setStatus('qwerty');

        $this->assertSame('qwerty', $this->error->getStatus());
    }

    public function testCode()
    {
        $this->error->setCode('123');

        $this->assertSame('123', $this->error->getCode());
    }

    public function testTitle()
    {
        $this->error->setTitle('qwerty');

        $this->assertSame('qwerty', $this->error->getTitle());
    }

    public function testDetail()
    {
        $this->error->setDetail('zxcvbn');

        $this->assertSame('zxcvbn', $this->error->getDetail());
    }

    public function testMetadata()
    {
        $this->assertInstanceOf(MetadataAwareInterface::class, $this->error);

        $this->error->setMetadataAttribute('test', 42);

        $this->assertFalse($this->error->hasMetadataAttribute('qwerty'));
        $this->assertTrue($this->error->hasMetadataAttribute('test'));
        $this->assertSame(42, $this->error->getMetadataAttribute('test'));
        $this->assertSame(['test' => 42], $this->error->getMetadata());
    }

    public function testLinks()
    {
        $this->assertInstanceOf(LinksAwareInterface::class, $this->error);

        $link = $this->createMock(LinkObject::class);
        $this->error->setLink('test', $link);

        $this->assertFalse($this->error->hasLink('qwerty'));
        $this->assertTrue($this->error->hasLink('test'));
        $this->assertSame($link, $this->error->getLink('test'));
        $this->assertSame(['test' => $link], $this->error->getLinks());
    }

    public function testToArrayBasics()
    {
        $this->error->setId('123');
        $this->error->setStatus('qwerty');
        $this->error->setCode('456');
        $this->error->setTitle('asdfgh');
        $this->error->setDetail('zxcvbn');

        $this->assertSame(
            [
                'id'     => '123',
                'status' => 'qwerty',
                'code'   => '456',
                'title'  => 'asdfgh',
                'detail' => 'zxcvbn'
            ],
            $this->error->toArray()
        );
    }

    public function testToArrayMetadata()
    {
        $this->error->setMetadataAttribute('test_attr', 'qwerty');

        $this->assertSame(
            ['test_attr' => 'qwerty'],
            $this->error->toArray()['meta']
        );
    }

    public function testToArrayLinks()
    {
        $link = $this->createMock(LinkObject::class);

        $link->expects($this->once())
            ->method('getReference')
            ->willReturn('http://qwerty.com');

        $this->error->setLink('test_link', $link);

        $this->assertSame(
            ['test_link' => 'http://qwerty.com'],
            $this->error->toArray()['links']
        );
    }
}