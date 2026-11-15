<?php
/**
 * @package     IO
 * @subpackage  DB\driver\mysql
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 10 01
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MySQLDBConnectionException extends DBConnectionException
{
    public function __construct(string $message)
    {
        parent::__construct($message . '. (ERRNO ' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }
}
?>
