<?php
/*##################################################
 *                        UserExtensionPoint.php
 *                            -------------------
 *   begin                : October 16 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : horn@phpboost.com
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