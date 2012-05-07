<?php
/*##################################################
 *                           SelectQueryResult.class.php
 *                            -------------------
 *   begin                : November 2, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/



/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package {@package}
 * @desc this class encapsulate a query result set
 * usage is:
 * <code>
 * foreach ($my_query_result as $result) {
 * 	   // do something with the $result
 * }
 * </code>
 */
interface SelectQueryResult extends QueryResult, iterator
{
	const FETCH_NUM = 0x00;
	const FETCH_ASSOC = 0x01;

	function set_fetch_mode($fetch_mode);

    /**
     * @desc returns the number of returned rows by this query
     * @return int the number of returned rows by this query
     */
    function get_rows_count();

	function has_next();

	function fetch();

    /**
     * @desc free the resource. If not done manually, this is done in the destructor
     */
    function dispose();
}
?>