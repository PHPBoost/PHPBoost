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

	function get_search_request($args);

	/**
	 * @desc Returns <code>true</code> if special search options could be applied to this module
	 * @return bool <code>true</code> if special search options could be applied to this module
	 */
	function has_search_options();

	function has_customized_results();
	
	/*function compute_search_results($args);
	
	function parse_search_result($result_data);
	
	function get_search_args();
	
	function get_search_form($args);
	*/
}
?>