<?php
/*##################################################
 *                          GuestbookModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : October 08, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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

class GuestbookModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}
	
	public function get_menu_id()
	{
		return 'module-mini-guestbook';
	}
	
	public function get_menu_title()
	{
		return LangLoader::get_message('module_title', 'common', 'guestbook');
	}
	
	public function is_displayed()
	{
		return !Url::is_current_url('/guestbook/') && GuestbookAuthorizationsService::check_authorizations()->read();
	}
	
	public function get_menu_content()
	{
		//Create file template
		$tpl = new FileTemplate('guestbook/GuestbookModuleMiniMenu.tpl');
		
		//Assign the lang file to the tpl
		$tpl->add_lang(LangLoader::get('common', 'guestbook'));
		
		$tpl->put('U_GUESTBOOK',GuestbookUrlBuilder::home()->rel());
		
		$guestbook_cache = GuestbookMessagesCache::load();
		$random_message = $guestbook_cache->get_message(array_rand($guestbook_cache->get_messages()));
		
		if ($random_message !== null)
		{
			$user_group_color = User::get_group_color($random_message['groups'], $random_message['level']);
			
			$tpl->put_all(array(
				'C_ANY_MESSAGE_GUESTBOOK' => true,
				'C_USER_GROUP_COLOR' => !empty($user_group_color),
				'C_MORE_CONTENTS' => strlen($random_message['contents']) >= 200,
				'C_VISITOR' => empty($random_message['user_id']),
				'CONTENTS' => $random_message['contents'],
				'SHORT_CONTENTS' => nl2br(TextHelper::substr_html($random_message['contents'], 0, 200)),
				'USER_PSEUDO' => $random_message['login'],
				'USER_LEVEL_CLASS' => UserService::get_level_class($random_message['level']),
				'USER_GROUP_COLOR' => $user_group_color,
				'U_MESSAGE' => GuestbookUrlBuilder::home($random_message['page'], $random_message['id'])->rel(),
				'U_PROFILE' => UserUrlBuilder::profile($random_message['user_id'])->rel(),
			));
		}
		
		return $tpl->render();
	}
}
?>