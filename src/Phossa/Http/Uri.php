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

use Phossa\Http\Message\Message;
use Psr\Http\Message\UriInterface;
use Phossa\Http\Exception\InvalidArgumentException;

/**
 * Uri
 *
 * @package Phossa\Http
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Uri implements UriInterface
{
    /**
     * Sub-delimiters used in query strings and fragments.
     *
     * @const string
     */
    const CHAR_SUB_DELIMS = '!\$&\'\(\)\*\+,;=';

    /**
     * Unreserved characters used in paths, query strings, and fragments.
     *
     * @const string
     */
    const CHAR_UNRESERVED = 'a-zA-Z0-9_\-\.~';

    /**
     * Not valide % encoded '%'
     *
     * @const string
     */
    const CHAR_NONENCODE  = '%(?![A-Fa-f0-9]{2})';

    /**
     * supported schemes
     *
     * @var    array
     * @access protected
     * @static
     */
    protected static $supported_schemes = [
        ''      => null,
        'http'  => 80,
        'https' => 443,
    ];

    /**
     * scheme, 'http|https|'
     *
     * @var    string
     * @access protected
     */
    protected $scheme = '';

    /**
     * host
     *
     * @var    string
     * @access protected
     */
    protected $host = '';

    /**
     * port
     *
     * @var    int
     * @access protected
     */
    protected $port;

    /**
     * path
     *
     * @var    string
     * @access protected
     */
    protected $path = '';

    /**
     * query
     *
     * @var    string
     * @access protected
     */
    protected $query = '';

    /**
     * fragment
     *
     * @var    string
     * @access protected
     */
    protected $fragment = '';

    /**
     * user:pass
     *
     * @var    string
     * @access protected
     */
    protected $user_info = '';

    /**
     * Constructor
     *
     * @param  string $uri
     * @throws InvalidArgumentException
     * @access public
     */
    public function __construct(/*# string */ $uri = '')
    {
        if ($uri) {
            $this->parseUri($uri);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthority()
    {
        if ($this->host) {
            $res = $this->host;
            if ($this->user_info) {
                $res = $this->user_info . '@' . $res;
            }
            $port = $this->getPort();
            if ($port) {
                $res .= ':' . $port;
            }
        }
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function getUserInfo()
    {
        return $this->user_info;
    }

    /**
     * {@inheritDoc}
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * {@inheritDoc}
     */
    public function getPort()
    {
        $scheme = $this->getScheme();
        if ($this->port && $this->port === self::$supported_schemes[$scheme]) {
            $this->port = null;
        }
        return $this->port;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritDoc}
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * {@inheritDoc}
     */
    public function withScheme($scheme)
    {
        if ($this->scheme === $scheme) {
            return $this;
        }

        $clone = clone $this;
        $clone->scheme = $this->filterScheme($scheme);
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function withUserInfo($user, $password = null)
    {
        $user_info = $user . ($user && $password ? (':' . $password) : '');
        if ($user_info === $this->user_info) {
            return $this;
        }
        $clone = clone $this;
        $clone->user_info = $user_info;
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function withHost($host)
    {
        if ($host === $this->host) {
            return $this;
        }

        $clone = clone $this;
        $clone->host = $this->filterHost($host);
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function withPort($port)
    {
        if ($port === $this->port) {
            return $this;
        }

        $clone = clone $this;
        $clone->port = $this->filterPort($port);
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function withPath($path)
    {
        if ($path === $this->path) {
            return $this;
        }

        $clone = clone $this;
        $clone->path = $this->filterPath($path);
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function withQuery($query)
    {
        if ($query === $this->query) {
            return $this;
        }

        $clone = clone $this;
        $clone->query = $this->filterQuery($query);
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function withFragment($fragment)
    {
        if ($fragment === $this->fragment) {
            return $this;
        }

        $clone = clone $this;
        $clone->fragment = $this->filterFragment($fragment);
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        $uri  = '';

        // scheme part
        $scheme = $this->getScheme();
        if ($scheme) {
            $uri .= $scheme . ':';
        }

        // authority
        $authority = $this->getAuthority();
        if ($authority) {
            $ur .= '//' . $authority;
        }

        // path
        $path = $this->getPath();
        if ($uri) {
            $path = '/' . ltrim($path, '/');
        }
        $uri .= $path;

        // query
        $query = $this->getQuery();
        if ($query) {
            $uri .= '?' . $query;
        }

        // fragment
        $fragment = $this->getFragment();
        if ($fragment) {
            $uri .= '#' . $fragment;
        }

        return $uri;
    }

    /**
     * Parse URI
     *
     * @param  string $uri
     * @throws InvalidArgumentException
     * @access protected
     */
    protected function parseUri(/*# string */ $uri)
    {
        $parts = parse_url($uri);
        if (false === $parts) {
            throw new InvalidArgumentException(
                Message::get(Message::URI_INVALID_URI, $uri),
                Message::URI_INVALID_URI
            );
        }

        if (isset($parts['scheme'])) {
            $this->scheme = $this->filterScheme($parts['scheme']);
        }

        if (isset($parts['host'])) {
            $this->host = $this->filterHost($parts['host']);
        }

        if (isset($parts['port'])) {
            $this->port = $this->filterPort($parts['port']);
        }

        if (isset($parts['path'])) {
            $this->path = $this->filterPath($parts['path']);
        }

        if (isset($parts['query'])) {
            $this->query = $this->filterQuery($parts['query']);
        }

        if (isset($parts['fragment'])) {
            $this->fragment = $this->filterQuery($parts['fragment']);
        }

        if (isset($parts['user'])) {
            $this->user_info = $parts['user'];
            if (isset($parts['pass'])) {
                $this->user_info .= ':' . $parts['pass'];
            }
        }
    }

    /**
     * Filter scheme
     *
     * @param  string $scheme
     * @return string
     * @throws InvalidArgumentException
     * @access protected
     */
    protected function filterScheme(/*# string */ $scheme)/*# : string */
    {
        if (!is_string($scheme) || preg_match("/[^a-zA-Z\s]/", $scheme)) {
            throw new InvalidArgumentException(
                Message::get(Message::URI_INVALID_SCHEME, $scheme),
                Message::URI_INVALID_SCHEME
            );
        }
        return strtolower(trim($scheme));
    }

    /**
     * Filter host
     *
     * @param  string $host
     * @return string
     * @throws InvalidArgumentException
     * @access protected
     */
    protected function filterHost(/*# string */ $host)/*# : string */
    {
        // fully qualified hostname & localhostname
        $sub = '[a-z0-9]{1,63}|[a-z0-9][-a-z0-9]{0,61}[a-z0-9]'; // subdomain
        $top = '[a-zA-Z]{2,6}[.]?'; // top domain
        $pat = sprintf('/^\s*(?:(?i:%s\.)+%s|(?i:%s))\s*$/', $sub, $top, $sub);

        if (!is_string($host) || !preg_match($pat, $host) || strlen($host) > 253
        ) {
            throw new InvalidArgumentException(
                Message::get(Message::URI_INVALID_HOST, $host),
                Message::URI_INVALID_HOST
            );
        }
        return trim($host, " \t.");
    }

    /**
     * Filter port
     *
     * @param  int|null $port
     * @return int|null
     * @throws InvalidArgumentException
     * @access protected
     */
    protected function filterPort($port)
    {
        if (is_null($port) || is_int($port) && $port > 0  && $port < 65536) {
            return $port;
        } else {
            throw new InvalidArgumentException(
                Message::get(Message::URI_INVALID_PORT, $port),
                Message::URI_INVALID_PORT
            );
        }
    }

    /**
     * Filter path
     *
     * @param  string $path
     * @return string
     * @throws InvalidArgumentException
     * @access protected
     */
    protected function filterPath(/*# string */ $path)/*# : string */
    {
        if (!is_string($path)) {
            throw new InvalidArgumentException(
                Message::get(Message::URI_INVALID_PATH, $path),
                Message::URI_INVALID_PATH
            );
        }

        $pattern = '/' .
            '[^' . self::CHAR_UNRESERVED . self::CHAR_SUB_DELIMS . '%:@\/]++' .
            '|' . self::CHAR_NONENCODE . '/';

        $path = preg_replace_callback(
            $pattern, [$this, 'urlEncodeChar'], $path
        );

        if (empty($path) || $path[0] !== '/') {
            return $path;
        } else {
            return '/' . ltrim($path, '/');
        }
    }

    /**
     * Filter query
     *
     * @param  string $query
     * @return string
     * @throws InvalidArgumentException
     * @access protected
     */
    protected function filterQuery(/*# string */ $query)/*# : string */
    {
        if (!is_string($query)) {
            throw new InvalidArgumentException(
                Message::get(Message::URI_INVALID_QUERY, $query),
                Message::URI_INVALID_QUERY
            );
        }

        $pattern = '/' .
            '[^' . self::CHAR_UNRESERVED . self::CHAR_SUB_DELIMS . '%:@\/\?]++' .
            '|' . self::CHAR_NONENCODE . '/';

        return preg_replace_callback(
            $pattern, [$this, 'urlEncodeChar'], $query
        );
    }

    /**
     * URL encode the matched part
     *
     * @param  array $matches
     * @return string
     * @access protected
     */
    protected function urlEncodeChar(array $matches)
    {
        return rawurlencode($matches[0]);
    }
}
