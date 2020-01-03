<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 03
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

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
		return LangLoader::get_message('guestbook.module.title', 'common', 'guestbook');
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

		//Assign common menu variables to the tpl
		MenuService::assign_positions_conditions($tpl, $this->get_block());

		$tpl->put('U_GUESTBOOK',GuestbookUrlBuilder::home()->rel());

		$guestbook_cache = GuestbookCache::load();
		$messages = $guestbook_cache->get_messages();

		if (!empty($messages))
		{
			$random_message = $guestbook_cache->get_message(array_rand($messages));

			if ($random_message !== null)
			{
				$user_group_color = User::get_group_color($random_message['groups'], $random_message['level']);

				$tpl->put_all(array(
					'C_ANY_MESSAGE_GUESTBOOK' => true,
					'C_USER_GROUP_COLOR' => !empty($user_group_color),
					'C_MORE_CONTENTS' => TextHelper::strlen($random_message['contents']) >= 200,
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
		}

		return $tpl->render();
	}
}
?>
