<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Library
 * @package   Phossa\Http
 * @copyright 2015 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */
/*# declare(strict_types=1); */

namespace Phossa\Http;

use Psr\Http\Message\StreamInterface;
use Phossa\Http\Message\Message as ErrorMessage;
use Phossa\Http\Exception\InvalidArgumentException;

/**
 * HttpMessageTrait
 *
 * @package Phossa\Http
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
trait HttpMessageTrait
{
    /**
     * HTTP protocol version
     *
     * @var    string
     * @access protected
     */
    protected $protocol = '1.1';

    /**
     * Original headers
     *
     * @var    array
     * @access protected
     */
    protected $headers  = [];

    /**
     * Lower case to original header key map
     *
     * @var    array
     * @access protected
     */
    protected $header_map = [];

    /**
     * message body
     *
     * @var    StreamInterface
     * @access protected
     */
    protected $stream;

    /**
     * {@inheritDoc}
     */
    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    /**
     * {@inheritDoc}
     */
    public function withProtocolVersion($version)
    {
        // check version
        $allowed = [ '1.0', '1.1' ];
        if (in_array($version, $allowed)) {
            $clone = clone $this;
            $clone->protocol = $version;
            return $clone;
        }

        // invalid protocol version
        throw new InvalidArgumentException(
            ErrorMessage::get(ErrorMessage::HTTP_INVALID_PROTOCOL, $version),
            ErrorMessage::HTTP_INVALID_PROTOCOL
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * {@inheritDoc}
     */
    public function hasHeader($name)
    {
        return isset($this->header_map[strtolower($name)]);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader($name)
    {
        if ($this->hasHeader($name)) {
            $map = $this->header_map[strtolower($name)];
            return $this->headers[$map];
        }
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaderLine($name)
    {
        return implode(', ', $this->getHeader($name));
    }

    /**
     * {@inheritDoc}
     */
    public function withHeader($name, $value)
    {
        // convert to array first
        if (is_scalar($value)) {
            $value = [ $value ];
        }

        // validate header & value
        if (!RFC7230::isValidName($name) ||
            !RFC7230::isValidValue($value)
        ) {
            throw new InvalidArgumentException(
                ErrorMessage::get(ErrorMessage::HTTP_INVALID_HEADER, $name),
                ErrorMessage::HTTP_INVALID_HEADER
            );
        }

        $clone = clone $this;

        $lower = strtolower($name);
        if ($clone->hasHeader($name)) {
            unset($clone->headers[$clone->header_map[$lower]]);
        }
        $clone->header_map[$lower] = $name;
        $clone->headers[$name] = is_array($value) ? $value : [ $value ];

        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function withAddedHeader($name, $value)
    {
        if ($this->hasHeader($name)) {
            $value = array_merge(
                $this->getHeader($name),
                is_array($value) ? $value : [ $value ]
            );
        }
        return $this->withHeader($name, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function withoutHeader($name)
    {
        if ($this->hasHeader($name)) {
            $clone = clone $this;
            $lower = strtolower($name);
            unset(
                $clone->headers[$clone->header_map[$lower]],
                $clone->header_map[$lower]
            );
            return $clone;
        } else {
            return $this;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getBody()
    {
        return is_null($this->stream) ? '' : $this->stream;
    }

    /**
     * {@inheritDoc}
     */
    public function withBody(StreamInterface $body)
    {
        if ($body === $this->stream) {
            return $this;
        }

        $clone = clone $this;
        $clone->stream = $body;
        return $clone;
    }
}
