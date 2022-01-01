<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 02 12
 * @since       PHPBoost 3.0 - 2010 10 16
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface UserExtensionPoint extends ExtensionPoint
{
	const EXTENSION_POINT = 'user';

	/**
	 * @desc Returns the link to the page on which user messages for the current module will be displayed
	 * @param int $user_id the user id
	 * @return the link
	 */
	function get_publications_module_view($user);

	/**
	 * @desc Returns the link name of the page on which user messages that will be displayed
	 * @return the link name
	 */
	function get_publications_module_name();

	/**
	 * @desc Returns the folder name of the module
	 * @return the folder name
	 */
	function get_publications_module_id();

	/**
	 * @desc Returns the picture associated with the link of the page on which user messages will be displayed
	 * @return the link picture
	 */
	function get_publications_module_icon();

	/**
	 * @return int number messages
	 */
	function get_publications_number($user_id);
}
?>
