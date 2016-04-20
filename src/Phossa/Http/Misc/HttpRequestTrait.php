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

namespace Phossa\Http\Misc;

use Phossa\Http\Message\Message;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\RequestInterface;
use Phossa\Http\Exception\InvalidArgumentException;

/**
 * Http request trait
 *
 * @package Phossa\Http
 * @author  Hong Zhang <phossa@126.com>
 * @see     RequestInterface
 * @see     HttpMessageTrait
 * @version 1.0.0
 * @since   1.0.0 added
 */
trait HttpRequestTrait
{
    use HttpMessageTrait;

    /**
     * HTTP method in UPPERCASE
     *
     * @var    string
     * @access protected
     */
    protected $method;

    /**
     * The URI
     *
     * @var    UriInterface
     * @access protected
     */
    protected $uri;

    /**
     * Request target
     *
     * @var    string
     * @access protected
     */
    protected $target;


    /**
     * {@inheritDoc}
     */
    public function getRequestTarget()
    {
        if (null !== $this->target) {
            return $this->target;
        }

        $target = $this->uri->getPath();
        if ($target === '') {
            $target = '/';
        }

        if ($this->uri->getQuery()) {
            $target .= '?' . $this->uri->getQuery();
        }

        return $target;
    }

    /**
     * {@inheritDoc}
     */
    public function withRequestTarget($requestTarget)
    {
        if (preg_match('#\s#', $requestTarget)) {
            throw new InvalidArgumentException(
                Message::get(Message::HTTP_INVALID_TARGET, $requestTarget),
                Message::HTTP_INVALID_TARGET
            );
        }
        $clone = clone $this;
        $clone->target = $requestTarget;
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * {@inheritDoc}
     */
    public function withMethod($method)
    {
        if ($method === $this->getMethod()) {
            return $this;
        }
        $clone = clone $this;
        $clone->method = $this->filterMethod($method);
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * {@inheritDoc}
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $clone   = clone $this;
        $clone->uri = $uri;
        return $clone->updateHost($uri, $preserveHost);
    }

    /**
     * filtering method
     *
     * @param  string $method
     * @return string
     * @throws InvalidArgumentException
     * @access protected
     */
    protected function filterMethod($method)
    {
        if (!is_string($method) ||
            !in_array(strtoupper($method), Rfc7231::$methods)
        ) {
            throw new InvalidArgumentException(
                Message::get(Message::HTTP_INVALID_METHOD, $method),
                Message::HTTP_INVALID_METHOD
            );
        }
        return strtoupper($method);
    }

    /**
     * Update 'Host' header of $this
     *
     * @param  UriInterface $uri
     * @param  bool $preserveHost
     * @return $this
     * @access protected
     */
    protected function updateHost(
        UriInterface $uri,
        /*# bool */ $preserveHost = false
    ) {
        $newHost = $uri->getHost();
        $hasHost = $this->hasHeader('host') && '' !== $this->getHeader('host');

        // update Host header ?
        $update  = $preserveHost && $hasHost ? false : true;
        if ($update && '' !== $newHost) {
            $this->setHeader('Host', $newHost);
        }

        return $this;
    }
}
