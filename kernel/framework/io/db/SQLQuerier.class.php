<?php
/*##################################################
 *                           SQLQuerier.class.php
 *                            -------------------
 *   begin                : October 1, 2009
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
 * @desc
 *
 */
interface SQLQuerier
{
	const ORDER_BY_ASC = 'ASC';
	const ORDER_BY_DESC = 'DESC';

	/**
	 * @desc executes the <code>$query</code> sql request and returns the query result.
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
	 * @desc executes the <code>$query</code> sql request.
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