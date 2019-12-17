<?php
/**
 * @package     Content
 * @subpackage  Search
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 08
*/

interface SearchableExtensionPoint extends ExtensionPoint
{
	const EXTENSION_POINT = 'search';

	function get_search_request($args);

	/**
	 * Returns <code>true</code> if special search options could be applied to this module
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
