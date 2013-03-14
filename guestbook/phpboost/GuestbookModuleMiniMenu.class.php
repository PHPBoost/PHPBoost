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
	
	public function display($tpl = false)
	{
		if (!Url::is_current_url('/guestbook/'))
		{
			$lang = LangLoader::get('guestbook_common', 'guestbook');
			$guestbook_cache = GuestbookMessagesCache::load();
			$guestbook_msgs_cache = $guestbook_cache->get_messages();
			$tpl = new FileTemplate('guestbook/GuestbookModuleMiniMenu.tpl');
			$tpl->add_lang($lang);
			MenuService::assign_positions_conditions($tpl, $this->get_block());
			
			$rand = array_rand($guestbook_msgs_cache);
			$guestbook_rand = isset($guestbook_msgs_cache[$rand]) ? $guestbook_cache->get_message($rand) : null;
			
			if ($guestbook_rand === null)
			{
				$tpl->put_all(array(
					'C_ANY_MESSAGE_GUESTBOOK' => false,
					'LINK_GUESTBOOK' => GuestbookUrlBuilder::home()->absolute()
				));
			}
			else
			{
				//Pseudo.
				if ($guestbook_rand['user_id'] != -1)
					$guestbook_login = '<a class="small_link" href="' . UserUrlBuilder::profile($guestbook_rand['user_id'])->absolute() . '" title="' . $guestbook_rand['login'] . '"><span style="font-weight:bold;">' . TextHelper::wordwrap_html($guestbook_rand['login'], 13) . '</span></a>';
				else
					$guestbook_login = '<span style="font-style:italic;">' . (!empty($guestbook_rand['login']) ? TextHelper::wordwrap_html($guestbook_rand['login'], 13) : LangLoader::get_message('guest', 'main')) . '</span>';
				
				$tpl->put_all(array(
					'C_ANY_MESSAGE_GUESTBOOK' => true,
					'RAND_MSG_ID' => $guestbook_rand['id'],
					'RAND_MSG_CONTENTS' => (strlen($guestbook_rand['contents']) > 149) ? $guestbook_rand['contents'] . ' <a href="' . GuestbookUrlBuilder::home('#m' . $guestbook_rand['id'])->absolute() . '" class="small_link">' . $lang['guestbook.titles.more_contents'] . '</a>' : $guestbook_rand['contents'],
					'RAND_MSG_LOGIN' => $guestbook_login,
					'L_BY' => LangLoader::get_message('by', 'main'),
					'LINK_GUESTBOOK' => GuestbookUrlBuilder::home('#m' . $guestbook_rand['id'])->absolute()
				));
			}
			return $tpl->render();
		}
		return '';
	}
}
?>