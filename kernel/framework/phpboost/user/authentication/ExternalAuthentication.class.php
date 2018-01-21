<?php
/*##################################################
 *		                        ExternalAuthentication.class.php
 *                            -------------------
 *   begin                : January 08, 2018
 *   copyright            : (C) 2018 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
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
	 * @return AuthenticationMethod class
	 */
	function get_authentication();
}
?>