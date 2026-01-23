<?php
/**
 * @package     Util
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 06
*/

class NotYetImplementedException extends Exception
{
    /**
     * Constructs the exception with an optional custom message.
     *
     * @param string|null $message Optional custom message to append
     */
    public function __construct(?string $message = null)
    {
        parent::__construct('not yet implemented' . ($message !== null ? ': ' . $message : ''));
    }
}
?>
