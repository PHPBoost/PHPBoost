<?php
/*##################################################
 *                       SearchableExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 08, 2010
 *   copyright            : (C) 2010 Loic Rouchon
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

interface SearchableExtensionPoint extends ExtensionPoint
{
	const EXTENSION_POINT = 'search';

	/**
	 * @desc
	 * @param string $search_text
	 * @return
	 */
	function get_search_request($search_text);

	/**
	 * @desc Returns <code>true</code> if special search options could be applied to this module
	 * @return bool <code>true</code> if special search options could be applied to this module
	 */
	function has_search_options();

	/**
	 * @desc Returns a FormFieldset containing the form inputs to handle the special search options
	 *   for this module. This method should only be called if the <code>has_search_options()</code>.
	 * @return FormFieldset the form fieldset
	 */
	function build_search_form();

	/**
	 * @desc Returns <code>true</code> if each element has to be inserted in a html list in the
	 *   specialized result presentation. If <code>false</false>, elements will be put one after
	 *   each another, with no html separator.
	 * @return bool <code>true</code> if each element has to be inserted in a html list in the
	 *   specialized result presentation
	 */
	function build_output_as_list();

	/**
	 * @desc Returns a View that will be inserted in the specialized result list for the element of
	 *   id <code>$element_id</code>.
	 * @param SearchResult the search result that will be formatted for output
	 * @return View the View that will be inserted in the specialized result list
	 */
	function format_element(SearchResult $result);
}
?>