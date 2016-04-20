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

use Phossa\Shared\Pattern\StaticAbstract;

/**
 * RFC7230 validations
 *
 * @package Phossa\Http
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Rfc7230 extends StaticAbstract
{
    /**
     * Valid header name ?
     *
     * @param  string $header
     * @return bool
     * @access public
     * @static
     */
    public static function isValidName(/*# string */ $header)/*# : bool */
    {
        return is_string($header) && 0 === strlen(str_replace(
            "a-zA-Z0-9'`#$%&*+.^_|~!-", '', $header
        ));
    }

    /**
     * Valid header value ?
     *
     * @param  string|string[] $value
     * @return bool
     * @access public
     */
    public static function isValidValue($value)/*# : bool */
    {
        /*
         * CRLF attacks
         *
         * \n not preceded by \r
         * \r not followed by \n, OR
         * \r\n not followed by space or horizontal tab
         *
         * Allowed chars
         *
         * 9   = horizontal tab
         * 10  = line feed
         * 13  = carriage return
         * 32-126, 128-254 = visible
         */
        $pattern = sprintf("#%s|%s#",
            "(?<!\r)\n|\r(?!\n)|\r\n(?![ \t])",
            "[^\x09\x0a\x0d\x20-\x7E\x80-\xFE]"
        );

        if (is_scalar($value)) {
            $value = [ $value ];
        }

        foreach ($value as $val) {
            if (!is_string($val) || preg_match($pattern, $val)) {
                return false;
            }
        }
        return true;
    }
}
