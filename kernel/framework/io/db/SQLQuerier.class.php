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

interface SQLQuerier
{
	const ORDER_BY_ASC = 'ASC';
	const ORDER_BY_DESC = 'DESC';

	/**
	 * executes the <code>$query</code> sql request and returns the query result.
	 * <p>Query will first be converted into the specific sgbd dialect.</p>
	 * <p>Next query functions will be converted into the specific sgbd dialect.</p>
	 * <p>Then query vars ":sample_query_var" will be replaced by the value of
	 * the <code>$parameters['sample_query_var']</code> variable if existing.
	 * If not (there's a lot of chance that you have forgotten to register this
	 * query var in the <code>$parameters</code> map), the query var won't be replaced</p>
	 * @param string $query the query to execute
	 * @param string[string] $parameters the query_var map
	 * @return SelectQueryResult the query result set
	 */
	function select($query, $parameters = array(), $fetch_mode = SelectQueryResult::FETCH_ASSOC);

	/**
	 * executes the <code>$query</code> sql request.
	 * <p>Query will first be converted into the specific sgbd dialect.</p>
	 * <p>Next query functions will be converted into the specific sgbd dialect.</p>
	 * <p>Then query vars ":sample_query_var" will be replaced by the value of
	 * the <code>$parameters['sample_query_var']</code> variable if existing.
	 * If not (there's a lot of chance that you have forgotten to register this
	 * query var in the <code>$parameters</code> map), the query var won't be replaced</p>
	 * @param string $query the query to execute
	 * @param string[string] $parameters the query_var map
	 * @return InjectQueryResult the query result
	 */
	function inject($query, $parameters = array());

	function enable_query_translator();

	function disable_query_translator();

	function get_executed_requests_count();
}
?>
