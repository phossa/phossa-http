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

use Phossa\Http\Misc\HttpRequestTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 *
 *
 * @package Phossa\Http
 * @author  Hong Zhang <phossa@126.com>
 * @see     ServerRequestInterface
 * @see     HttpRequestTrait
 * @version 1.0.0
 * @since   1.0.0 added
 */
class ServerRequest extends Request implements ServerRequestInterface
{
    use HttpRequestTrait;

    /**
     * Attributes
     *
     * @var    array
     * @access protected
     */
    protected $attributes = [];

    /**
     * Server related parameters
     *
     * @var    array
     * @access protected
     */
    protected $server_params;

    /**
     * Cookie related parameters
     *
     * @var    array
     * @access protected
     */
    protected $cookie_params;

    /**
     * Query related parameters
     *
     * @var    array
     * @access protected
     */
    protected $query_params;

    /**
     * Uploaded fileds
     *
     * @var    UploadedFileInterface[]
     * @access protected
     */
    protected $uploaded_files = [];

    /**
     * parsed body parameters
     *
     * @var    array
     * @access protected
     */
    protected $parsed_body;

    /**
     * Create a Request from current PHP environment OR
     * useing current environment with a new URI (a sub-request)
     *
     * @param  string|UriInterface $uri
     * @param  string $method
     * @param  array $headers
     * @param  string|resource|StreamInterface $body
     * @throws InvalidArgumentException
     * @access public
     */
    public function __construct(
        $uri = null,
        /*# string */ $method = 'GET',
        array $headers = [],
        $body = null
    ) {
        if (is_null($uri)) {
            $this->createFromGlobals();
        } else {
            parent::__construct($uri, $method, $headers, $body);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getServerParams()
    {
        return $this->server_params;
    }

    /**
     * {@inheritDoc}
     */
    public function getCookieParams()
    {
        return $this->cookie_params;
    }

    /**
     * {@inheritDoc}
     */
    public function withCookieParams(array $cookies)
    {
        $clone = clone $this;
        $clone->cookie_params = $cookies;
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryParams()
    {
        return $this->query_params;
    }

    /**
     * {@inheritDoc}
     */
    public function withQueryParams(array $query)
    {
        $clone = clone $this;
        $clone->query_params = $query;
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function getUploadedFiles()
    {
        return $this->uploaded_files;
    }

    /**
     * {@inheritDoc}
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
         $clone = clone $this;
         $clone->uploaded_files = $uploadedFiles;
         return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function getParsedBody()
    {
        return $this->parsed_body;
    }

    /**
     * {@inheritDoc}
     */
    public function withParsedBody($data)
    {
        $clone = clone $this;
        $clone->parsed_body = $data;
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute($name, $default = null)
    {
        if (!array_key_exists($name, $this->attributes)) {
            return $default;
        }
        return $this->attributes[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function withAttribute($name, $value)
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function withoutAttribute($name)
    {
        if (null === $this->getAttribute($name)) {
            return $this;
        }
        $clone = clone $this;
        unset($clone->attributes[$name]);
        return $clone;
    }

    /**
     * Create Request object from the PHP GLBOALS
     *
     * @access protected
     */
    protected function createFromGlobals()
    {
        // create uri
        $this->uri = new Uri();

        // method
        $this->method = $_SERVER['REQUEST_METHOD'];

        // headers
        foreach ($this->headersFromServer() as $name => $value) {
            $this->setHeader($name, $value);
        }

        // protocol
        list(, $this->protocol) = explode('/', $_SERVER['SERVER_PROTOCOL'], 2);

        // server
        $this->server_params = $_SERVER;

        // cookies
        $this->cookie_params = $_COOKIE;

        // get
        $this->query_params = $_GET;

        // post
        $this->parsed_body = $_POST;
    }

    /**
     * Create headers array from $_SERVER
     *
     * @access protected
     */
    protected function headersFromServer()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if ('HTTP_' === substr($name, 0, 5)) {
                $name = str_replace(
                    ' ',
                    '-',
                    ucfirst(
                        str_replace('_', ' ', strtolower(substr($name, 5)))
                    )
                );
                $headers[$name] = preg_split("/,\s*/", $value);
            }
        }
        return $headers;
    }
}
