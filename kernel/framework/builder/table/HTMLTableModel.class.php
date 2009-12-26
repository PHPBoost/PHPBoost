<?php
/*##################################################
 *                           HTMLTableModel.class.php
 *                            -------------------
 *   begin                : December 26, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @package builder
 * @subpackage table
 */
interface HTMLTableModel
{
	function get_id();

	function get_caption();

	function get_nb_rows_per_page();

	function get_columns();

	/**
	 * @desc Returns the default sorting rule if none is specified
	 * @return HTMLTableSortRule the default sorting rule if none is specified
	 */
	function default_sort_rule();

	function get_number_of_matching_rows(array $filters);

	/**
	 * @desc Returns up to <code>$limit</code> rows starting from the <code>$offset</code> one.
	 * Rows are sorted using the <code>$sorting_rule</code> and filtered with <code>$filters</code> rules
	 * @param int $limit the maximum number of rows to retrieve
	 * @param int $offset the offset from which rows will be retrieved
	 * @param HTMLTableSortRule $sorting_rule the sorting rule
	 * @param HTMLTableFilter[] $filters the filter to apply
	 * @return HTMLTableRow[] the requested rows
	 */
	function get_rows($limit, $offset, HTMLTableSortRule $sorting_rule, array $filters);
}

?>