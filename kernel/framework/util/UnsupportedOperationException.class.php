<?php
/**
 * Implements the string var replacement method
 * @package     Util
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 02
*/

class UnsupportedOperationException extends Exception
{
    public function __construct($msg = 'operation is not supported')
    {
        parent::__construct($msg);
    }
}
?>
