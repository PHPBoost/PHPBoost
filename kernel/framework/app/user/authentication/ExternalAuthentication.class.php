<?php
/**
 * @package     PHPBoost
 * @subpackage  User\authentication
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 5.1 - 2018 01 08
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface ExternalAuthentication
{
	/**
	 * @return string Unique identifier for the name of the authentication system
	 */
	function get_authentication_id(): string;

	/**
	 * @return string Authentication name
	 */
	function get_authentication_name(): string;

	/**
	 * @return bool true if the authentication system is enabled
	 */
	function authentication_actived(): bool;

	/**
	 * @return string contains HTML tag image rendering
	 */
	function get_image_renderer_html(): string;

	/**
	 * @return string contains css class for button rendering
	 */
	function get_css_class(): string;

	/**
	 * @return AuthenticationMethod class
	 */
	function get_authentication(): AuthenticationMethod;

	function delete_session_token(): void;
}
?>
