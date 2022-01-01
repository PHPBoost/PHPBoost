<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 09 20
 * @since       PHPBoost 5.1 - 2018 04 10
*/

interface SocialNetwork
{
	/**
	 * @return string Name of the social network
	 */
	function get_name();

	/**
	 * @return string Icon of the social network (usually its id)
	 */
	function get_icon_name();

	/**
	 * @return string CSS class name of the social network (usually its id)
	 */
	function get_css_class();

	/**
	 * @return bool true if the social network has a content sharing url
	 */
	function has_content_sharing_url();

	/**
	 * @return string Content sharing url
	 */
	function get_content_sharing_url();

	/**
	 * @return bool true if the social network has a content sharing url for mobile devices
	 */
	function has_mobile_content_sharing_url();

	/**
	 * @return string Content sharing url for mobile devices
	 */
	function get_mobile_content_sharing_url();

	/**
	 * @return bool true if the social network must only be displayed on mobile devices
	 */
	function is_mobile_only();

	/**
	 * @return bool true if the social network must only be displayed on desktop devices
	 */
	function is_desktop_only();

	/**
	 * @return string contains HTML share tag image rendering
	 */
	function get_share_image_renderer_html();

	/**
	 * @return bool true if the social network has an authentication method
	 */
	function has_authentication();

	/**
	 * @return ExternalAuthentication class
	 */
	function get_external_authentication();

	/**
	 * @return bool true if the social network needs identifiers (API keys) to be configured
	 */
	function authentication_identifiers_needed();

	/**
	 * @return bool true if the social network needs API secret with the API Key / ID to be configured
	 */
	function authentication_client_secret_needed();

	/**
	 * @return string Identifiers creation url
	 */
	function get_identifiers_creation_url();

	/**
	 * @return bool true if the social network needs a callback url in its configuration
	 */
	function callback_url_needed();
}
?>
