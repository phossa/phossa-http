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
    const HTTP_INVALID_PROTOCOL     = 1604181353;

    /**
     * Invalid HTTP header or value "%s"
     */
    const HTTP_INVALID_HEADER       = 1604181354;

    /**#@-*/

    /**
     * {@inheritdoc}
     */
    protected static $messages = [
        self::HTTP_INVALID_PROTOCOL => 'Invalid HTTP protocol version "%s"',
        self::HTTP_INVALID_HEADER   => 'Invalid HTTP header or value "%s"',
    ];
}
