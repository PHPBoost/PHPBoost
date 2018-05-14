<?php
/*##################################################
 *		                        SocialNetwork.class.php
 *                            -------------------
 *   begin                : April 10, 2018
 *   copyright            : (C) 2018 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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