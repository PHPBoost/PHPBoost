<?php
/**
 * this class encapsulate a query result set
 * usage is:
 * <code>
 * foreach ($my_query_result as $result) {
 * 	   // do something with the $result
 * }
 * </code>
 * @package     IO
 * @subpackage  DB
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 11 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface SelectQueryResult extends QueryResult, iterator
{
	const FETCH_NUM = 0x00;
	const FETCH_ASSOC = 0x01;

	function set_fetch_mode(int $fetch_mode): void;

    /**
     * returns the number of returned rows by this query
     * @return int the number of returned rows by this query
     */
    function get_rows_count(): int;

	function has_next(): bool;

	function fetch(): mixed;

    /**
     * free the resource. If not done manually, this is done in the destructor
     */
    function dispose(): void;
}
?>
