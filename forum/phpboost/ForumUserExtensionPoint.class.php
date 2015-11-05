<?php
/*##################################################
 *                        ForumUserExtensionPoint.php
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

class ForumUserExtensionPoint implements UserExtensionPoint
{
	/**
	 * {@inheritDoc}
	 */
	public function get_messages_list_url($user_id)
	{
		return Url::to_rel('/forum/membermsg.php?id=' . $user_id);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_messages_list_link_name()
	{
		global $LANG;
		load_module_lang('forum');

		return $LANG['forum'];
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_messages_list_link_img()
	{
		return '<img src="' . TPL_PATH_TO_ROOT . '/forum/forum_mini.png" alt="forum_mini" class="valign-middle" />';
	}
	
	public function get_number_messages($user_id)
	{
		$parameters = array('user_id' => $user_id);
		return PersistenceContext::get_querier()->count(PREFIX . 'forum_msg', 'WHERE user_id = :user_id', $parameters);
	}
}
?>