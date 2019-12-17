<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 16
*/

interface UserExtensionPoint extends ExtensionPoint
{
	const EXTENSION_POINT = 'user';

	/**
	 * @desc Returns the link to the page on which user messages for the current module will be displayed
	 * @param int $user_id the user id
	 * @return the link
	 */
	function get_messages_list_url($user);

	/**
	 * @desc Returns the link name of the page on which user messages that will be displayed
	 * @return the link name
	 */
	function get_messages_list_link_name();

	/**
	 * @desc Returns the picture associated with the link of the page on which user messages will be displayed
	 * @return the link picture
	 */
	function get_messages_list_link_img();

	/**
	 * @return int number messages
	 */
	function get_number_messages($user_id);
}
?>
