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

namespace Phossa\Http\Message;

use Phossa\Shared\Message\MessageAbstract;

/**
 * Message class for Phossa\Http
 *
 * @package \Phossa\Http
 * @author  Hong Zhang <phossa@126.com>
 * @see     MessageAbstract
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Message extends MessageAbstract
{
    /**#@+
     * @var   int
     */

    /**
     * Invalid HTTP protocol version "%s"
     */
    const URI_INVALID_PROTOCOL      = 1604181353;

    /**
     * Invalid HTTP header or value "%s"
     */
    const URI_INVALID_HEADER        = 1604181354;

    /**
     * Invalid HTTP scheme "%s"
     */
    const URI_INVALID_SCHEME        = 1604181355;

    /**
     * Invalid HTTP host "%s"
     */
    const URI_INVALID_HOST          = 1604181356;

    /**
     * Invalid HTTP port "%s"
     */
    const URI_INVALID_PORT          = 1604181357;

    /**
     * Invalid URI "%s"
     */
    const URI_INVALID_URI           = 1604181358;

    /**
     * Invalid URI path "%s"
     */
    const URI_INVALID_PATH          = 1604181359;

    /**
     * Invalid URI query "%s"
     */
    const URI_INVALID_QUERY         = 1604181360;

    /**
     * Invalid HTTP method "%s"
     */
    const HTTP_INVALID_METHOD       = 1604181361;

    /**
     * Invalid HTTP target "%s"
     */
    const HTTP_INVALID_TARGET       = 1604181362;

    /**
     * Invalid HTTP status code "%s"
     */
    const HTTP_INVALID_STATUS       = 1604181362;

    /**#@-*/

    /**
     * {@inheritdoc}
     */
    protected static $messages = [
        self::URI_INVALID_PROTOCOL  => 'Invalid URI protocol version "%s"',
        self::URI_INVALID_HEADER    => 'Invalid URI header or value "%s"',
        self::URI_INVALID_SCHEME    => 'Invalid URI scheme "%s"',
        self::URI_INVALID_HOST      => 'Invalid URI host "%s"',
        self::URI_INVALID_PORT      => 'Invalid URI port "%s"',
        self::URI_INVALID_URI       => 'Invalid URI "%s"',
        self::URI_INVALID_PATH      => 'Invalid URI path "%s"',
        self::URI_INVALID_QUERY     => 'Invalid URI query "%s"',
        self::HTTP_INVALID_METHOD   => 'Invalid HTTP method "%s"',
        self::HTTP_INVALID_TARGET   => 'Invalid HTTP target "%s"',
        self::HTTP_INVALID_STATUS   => 'Invalid HTTP status code "%s"',
    ];
}
