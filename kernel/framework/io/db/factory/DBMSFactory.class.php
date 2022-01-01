<?php
/**
 * This factory provides the <code>DBConnection</code> and the <code>SQLQuerier</code>
 * for the right sgbd.
 * @package     IO
 * @subpackage  DB\factory
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 11 10
*/

interface DBMSFactory
{
	/**
	 * returns the opened <code>DBConnection</code>. If not yet opened, opens it
	 * @return DBConnection the opened <code>DBConnection</code>. If not yet opened, opens it
	 */
	function new_db_connection();

	/**
	 * returns a new <code>SQLQuerier</code> instance
	 * @param DBConnection $db_connection the db connection that the <code>SQLQuerier</code> will use
	 * @return SQLQuerier a new <code>SQLQuerier</code> instance
	 */
	function new_sql_querier(DBConnection $db_connection);

	/**
	 * returns a new <code>DBMSUtils</code> instance
	 * @param SQLQuerier $querier the <code>SQLQuerier</code> that the <code>DBMSUtils</code> will use
	 * @return DBMSUtils a new <code>DBMSUtils</code> instance
	 */
	function new_dbms_util(SQLQuerier $querier);
}
?>
