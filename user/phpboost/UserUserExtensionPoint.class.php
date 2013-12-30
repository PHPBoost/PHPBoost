<?php
/*##################################################
 *                        UserUserExtensionPoint.php
 *                            -------------------
 *   begin                : October 09, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class UserUserExtensionPoint implements UserExtensionPoint
{
	public function get_messages_list_url($user_id)
	{
		return UserUrlBuilder::comments('', $user_id)->rel();
	}

	public function get_messages_list_link_name()
	{
		return LangLoader::get_message('comments', 'comments-common');
	}

	public function get_messages_list_link_img()
	{
		return '<i class="fa fa-comments-o"></i>';
	}
	
	public function get_number_messages($user_id)
	{
		$parameters = array('user_id' => $user_id);
		return PersistenceContext::get_querier()->count(DB_TABLE_COMMENTS, 'WHERE user_id = :user_id', $parameters);
	}
}
?>