<?php
declare(strict_types = 1);

namespace Mikemirten\Component\JsonApi\Document;

use Mikemirten\Component\JsonApi\Document\Behaviour\LinksAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\LinksContainer;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataAwareInterface;
use Mikemirten\Component\JsonApi\Document\Behaviour\MetadataContainer;

/**
 * Error object
 *
 * @see http://jsonapi.org/format/#errors
 *
 * @package Mikemirten\Component\JsonApi\Document
 */
class ErrorObject implements LinksAwareInterface, MetadataAwareInterface
{
    use LinksContainer;
    use MetadataContainer;

    /**
     * Identifier of occurrence
     *
     * @var string
     */
    protected $id;

    /**
     * HTTP status code
     *
     * @var string
     */
    protected $status;

    /**
     * Application specific code
     *
     * @var string
     */
    protected $code;

    /**
     * Human-readable summary
     *
     * @var string
     */
    protected $title;

    /**
     * Human-readable explanation
     *
     * @var string
     */
    protected $detail;

    /**
     * ErrorObject constructor.
     *
     * @param string $id
     * @param string $title
     */
    public function __construct(string $id = null, string $title = null)
    {
        $this->id    = $id;
        $this->title = $title;
    }

    /**
     * Set ID of occurrence
     *
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * Has ID of occurrence ?
     *
     * @return bool
     */
    public function hasId(): bool
    {
        return $this->id !== null;
    }

    /**
     * Get ID of occurrence
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set HTTP status code
     *
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * Has HTTP status code ?
     *
     * @return bool
     */
    public function hasStatus(): bool
    {
        return $this->status !== null;
    }

    /**
     * Get HTTP status code
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set application specific code
     *
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * Has application specific code
     *
     * @return bool
     */
    public function hasCode(): bool
    {
        return $this->code !== null;
    }

    /**
     * Get application specific code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Has title
     *
     * @return bool
     */
    public function hasTitle(): bool
    {
        return $this->title !== null;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set detailed explanation
     *
     * @param string $detail
     */
    public function setDetail(string $detail)
    {
        $this->detail = $detail;
    }

    /**
     * Has detailed explanation ?
     *
     * @return bool
     */
    public function hasDetail(): bool
    {
        return $this->detail !== null;
    }

    /**
     * Get detailed explanation
     *
     * @return string
     */
    public function getDetail(): string
    {
        return $this->detail;
    }

    /**
     * Cast to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = array_filter([
            'id'     => $this->id,
            'status' => $this->status,
            'code'   => $this->code,
            'title'  => $this->title,
            'detail' => $this->detail
        ]);

        if ($this->hasLinks()) {
            $data['links'] = $this->linksToArray();
        }

        if ($this->hasMetadata()) {
            $data['meta'] = $this->getMetadata();
        }

        return $data;
    }

    /**
     * Cast to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return 'Error-object with ' . (
            $this->id === null
                ? 'no ID'
                : sprintf('ID: "%s"', $this->id)
        );
    }
}