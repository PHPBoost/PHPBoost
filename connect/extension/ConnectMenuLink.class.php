<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 09
 * @since       PHPBoost 6.0 - 2022 02 09
*/

interface ConnectMenuLink extends ExtensionPoint
{
	const EXTENSION_POINT = 'connect_menu_link';

	/**
	 * @return string Menu name, will create a css class connect-"name"
	 */
	function get_menu_name();

	/**
	 * @return string Menu fa icon, return empty string to disable icon display
	 */
	function get_icon();

	/**
	 * @return string Menu label
	 */
	function get_label();

	/**
	 * @return int Level from which display the menu (User::MEMBER_LEVEL, User::MODERATOR_LEVEL or User::ADMINISTRATOR_LEVEL)
	 */
	function get_display_level();

	/**
	 * @return int Number of unread elements to display. Return 0 to disable it.
	 */
	function get_unread_elements_number();

	/**
	 * @return Url Menu link
	 */
	function get_url();
}
?>
