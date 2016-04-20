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

use Psr\Http\Message\UriInterface;
use Phossa\Http\Misc\HttpRequestTrait;
use Psr\Http\Message\RequestInterface;
use Phossa\Http\Exception\InvalidArgumentException;

/**
 * HTTP Request for client software
 *
 * @package Phossa\Http
 * @author  Hong Zhang <phossa@126.com>
 * @see     RequestInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Request implements RequestInterface
{
    use HttpRequestTrait;

    /**
     * Constructor
     *
     * @param  null|string|UriInterface $uri
     * @param  string $method
     * @param  array $headers
     * @param  string|resource|StreamInterface $body
     * @throws InvalidArgumentException
     * @access public
     */
    public function __construct(
        $uri = '',
        /*# string */ $method = 'GET',
        array $headers = [],
        $body = null
    ) {
        // generate uri
        $this->uri = $uri instanceof UriInterface ? $uri : new Uri($uri);

        // validate method
        $this->method = $this->filterMethod($method);

        // set headers
        foreach($headers as $name => $value) {
            $this->setHeader($name, $value);
        }

        // update 'Host' header if $uri has Host part
        $this->updateHost($this->uri);

        // update body
        if ($body) {
            $this->stream = $body;
        }
    }
}
