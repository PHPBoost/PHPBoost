<?php
/**
 * @package     PHPBoost
 * @subpackage  User\authentication
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 04 10
 * @since       PHPBoost 5.1 - 2018 01 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

interface ExternalAuthentication
{
	/**
	 * @return string Unique identifier for the name of the authentication system
	 */
	function get_authentication_id();

	/**
	 * @return string Authentication name
	 */
	function get_authentication_name();

	/**
	 * @return bool true if the authentication system is enabled
	 */
	function authentication_actived();

	/**
	 * @return string contains HTML tag image rendering
	 */
	function get_image_renderer_html();

	/**
	 * @return string contains css class for button rendering
	 */
	function get_css_class();

	/**
	 * @return AuthenticationMethod class
	 */
	function get_authentication();

	function delete_session_token();
}
?>
