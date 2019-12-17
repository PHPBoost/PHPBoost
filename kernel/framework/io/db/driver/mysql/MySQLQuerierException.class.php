<?php
/**
 * @package     IO
 * @subpackage  DB\driver\mysql
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 01
*/

class MySQLQuerierException extends SQLQuerierException
{
    public function __construct($message, $query)
    {
    	$link = PersistenceContext::get_querier()->get_querier()->get_link();
        parent::__construct($message . '. (ERRNO ' . mysqli_errno($link) . ') ' . mysqli_error($link) . '<hr />query: ' . $query);
    }
}
?>
