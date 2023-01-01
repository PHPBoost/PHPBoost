<?php
/**
 * @package     IO
 * @subpackage  DB
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 01
*/

interface QueryResult
{
	/**
	 * Returns the executed query converted to dbms dialect
	 * @return string the executed query converted to dbms dialect
	 */
	function get_query();

	/**
	 * Returns the parameters injected in the query
	 * @return mixed[string] the parameters injected in the query
	 */
	function get_parameters();
}
?>
