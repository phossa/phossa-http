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

use Phossa\Http\Misc\Rfc7231;
use Phossa\Http\Message\Message;
use Phossa\Http\Misc\HttpMessageTrait;
use Psr\Http\Message\ResponseInterface;
use Phossa\Http\Exception\InvalidArgumentException;

/**
 * Http response
 *
 * @package Phossa\Http
 * @author  Hong Zhang <phossa@126.com>
 * @see     ResponseInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Response implements ResponseInterface
{
    use HttpMessageTrait;

    /**
     * Status code
     *
     * @var    int
     * @access protected
     */
    protected $status = 200;

    /**
     * If not the default status phrase
     *
     * @var    string
     * @access protected
     */
    protected $phrase = '';

    /**
     * Constructor
     *
     * @param  int $status Status code
     * @param  array $headers
     * @param  string|resource|StreamInterface $body
     * @throws InvalidArgumentException
     * @access public
     */
    public function __construct(
        /*# int */ $status = 200,
        array $headers = [],
        $body = null
    ) {
        $this->status = $this->filterStatus($status);

        // set headers
        foreach($headers as $name => $value) {
            $this->setHeader($name, $value);
        }

        if ($body) {
            $this->stream = $body;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getStatusCode()
    {
        return $this->status;
    }

    /**
     * {@inheritDoc}
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        if ($code === $this->getStatusCode() &&
            $reasonPhrase === $this->phrase
        ) {
            return $this;
        }

        $clone = clone $this;
        $clone->status = $clone->filterStatus($code);
        $clone->phrase = (string) $reasonPhrase;
        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function getReasonPhrase()
    {
        return empty($this->phrase) ?
            Rfc7231::$status[$this->status] :
            $this->phrase;
    }

    /**
     * filter status code
     *
     * @param  int $code
     * @return int
     * @throws InvalidArgumentException
     * @access protected
     */
    protected function filterStatus(/*# int */ $code)/*# : int */
    {
        if (!is_int($code) || !isset(Rfc7231::$status[$code])) {
            throw new InvalidArgumentException(
                Message::get(Message::HTTP_INVALID_STATUS, $code),
                Message::HTTP_INVALID_STATUS
            );
        }
        return $code;
    }
}
