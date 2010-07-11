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
 * @package {@package}
 * @subpackage table
 */
interface HTMLTableModel
{
	/**
	 * @desc Returns the table id used to identify the table in the page
	 * @return string the table id
	 */
	function get_id();
	
	/**
	 * @desc Returns true if there is a caption
	 * @return bool true if there is a caption
	 */
	function has_caption();

	/**
	 * @desc Returns the table caption
	 * @return string the table caption
	 */
	function get_caption();
	
	/**
	 * @desc Returns true if the pagination is activated
	 * @return bool true if the pagination is activated
	 */
	function is_pagination_activated();

	/**
	 * @desc Returns the default number of items to print per page
	 * @return int the default number of items to print per page
	 */
	function get_nb_rows_per_page();
	
	/**
	 * @desc Returns true if it is possible to change the number of rows to display
	 * @return bool true if it is possible to change the number of rows to display
	 */
	function has_nb_rows_options();
	
	/**
	 * @desc Returns an array with the differents number of rows that could be displayed
	 * @return int[] the differents number of rows that could be displayed
	 */
	function get_nb_rows_options();

	/**
	 * @desc Returns the columns of the table
	 * @return HTMLColumn[] the columns of the table
	 */
	function get_columns();

	/**
	 * @desc Returns the default sorting rule if none is specified
	 * @return HTMLTableSortingRule the default sorting rule if none is specified
	 */
	function default_sort_rule();
	
	/**
	 * @desc Returns true if the sort parameter is allowed
	 * @param string $parameter the sort parameter name
	 * @return bool true if the sort parameter is allowed
	 */
	function is_sort_parameter_allowed($parameter);
	
	/**
	 * @desc Returns true if the value is allowed for this filter and sets its filter value
	 * @param string $id the filter id
	 * @param string $value the filter value
	 * @return bool true if the value is allowed for this filter
	 */
    function is_filter_allowed($id, $value);
    
    /**
     * @desc Returns the filter identified by the given id
     * @param string $id the filter id
     * @return HTMLTableFilter the filter identified by the given id
     */
    function get_filter($id);
    
    /**
     * @desc Returns all this model filters
     * @return HTMLTableFilter all this model filters
     */
    function get_filters();

	/**
	 * @desc Returns the number of matching rows
	 * @param HTMLTableFilter[] $filters filters
	 * @return int the number of matching rows
	 */
	function get_number_of_matching_rows(array $filters);

	/**
	 * @desc Returns up to <code>$limit</code> rows starting from the <code>$offset</code> one.
	 * Rows are sorted using the <code>$sorting_rule</code> and filtered with <code>$filters</code>
	 * rules
	 * @param int $limit the maximum number of rows to retrieve
	 * @param int $offset the offset from which rows will be retrieved
	 * @param HTMLTableSortingRule $sorting_rule the sorting rule (<code>null</code> if no rule specified)
	 * @param HTMLTableFilter[] $filters the filter to apply
	 * @return HTMLTableRow[] the requested rows
	 */
	function get_rows($limit, $offset, HTMLTableSortingRule $sorting_rule, array $filters);
}

?>