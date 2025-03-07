<?php
/**
 * @package     IO
 * @subpackage  DB\driver\mysql
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 01
*/

class MySQLUnexistingDatabaseException extends UnexistingDatabaseException
{
    public function __construct()
    {
        parent::__construct('(ERRNO ' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }
}
?>
