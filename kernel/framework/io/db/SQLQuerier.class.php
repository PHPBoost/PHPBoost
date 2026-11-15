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
	 * @param string[] $parameters the query_var map
	 * @return SelectQueryResult the query result set
	 */
	function select(string $query, array $parameters = [], int $fetch_mode = SelectQueryResult::FETCH_ASSOC): SelectQueryResult;

	/**
	 * executes the <code>$query</code> sql request.
	 * <p>Query will first be converted into the specific sgbd dialect.</p>
	 * <p>Next query functions will be converted into the specific sgbd dialect.</p>
	 * <p>Then query vars ":sample_query_var" will be replaced by the value of
	 * the <code>$parameters['sample_query_var']</code> variable if existing.
	 * If not (there's a lot of chance that you have forgotten to register this
	 * query var in the <code>$parameters</code> map), the query var won't be replaced</p>
	 * @param string $query the query to execute
	 * @param string[] $parameters the query_var map
	 * @return InjectQueryResult the query result
	 */
	function inject(string $query, array $parameters = []): InjectQueryResult;

	function enable_query_translator(): void;

	function disable_query_translator(): void;

	function get_executed_requests_count(): int;
}
?>
