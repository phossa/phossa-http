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
     * Invalid HTTP URI "%s"
     */
    const URI_INVALID_URI           = 1604181358;

    /**
     * Invalid HTTP PATH "%s"
     */
    const URI_INVALID_PATH          = 1604181359;

    /**
     * Invalid HTTP QUERY "%s"
     */
    const URI_INVALID_QUERY         = 1604181360;

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
        self::URI_INVALID_URI       => 'Invalid URI URI "%s"',
        self::URI_INVALID_PATH      => 'Invalid URI PATH "%s"',
        self::URI_INVALID_QUERY     => 'Invalid URI QUERY "%s"',
    ];
}
