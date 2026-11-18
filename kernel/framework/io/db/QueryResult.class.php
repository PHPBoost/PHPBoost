<?php
/**
 * @package     IO
 * @subpackage  DB
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 10 01
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface QueryResult
{
	/**
	 * Returns the executed query converted to dbms dialect
	 * @return string the executed query converted to dbms dialect
	 */
	function get_query(): string;

	/**
	 * Returns the parameters injected in the query
	 * @return mixed[] the parameters injected in the query
	 */
	function get_parameters(): array;
}
?>
