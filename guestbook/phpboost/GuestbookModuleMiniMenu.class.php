<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
		$view = new FileTemplate('guestbook/GuestbookModuleMiniMenu.tpl');

		//Assign the lang file to the tpl
		$view->add_lang(LangLoader::get_all_langs('guestbook'));

		//Assign common menu variables to the tpl
		MenuService::assign_positions_conditions($view, $this->get_block());

		$view->put('U_GUESTBOOK',GuestbookUrlBuilder::home()->rel());

		$guestbook_cache = GuestbookCache::load();
		$messages = $guestbook_cache->get_messages();

		if (!empty($messages))
		{
			$random_message = $guestbook_cache->get_message(array_rand($messages));

			if ($random_message !== null)
			{
				$user_group_color = User::get_group_color($random_message['user_groups'], $random_message['level']);

				$view->put_all(array(
					'C_ANY_MESSAGE_GUESTBOOK' => true,
					'C_AUTHOR_GROUP_COLOR'    => !empty($user_group_color),
					'C_SUMMARY'               => TextHelper::strlen($random_message['content']) >= 200,
					'C_VISITOR'               => empty($random_message['user_id']),

					'CONTENT'             => $random_message['content'],
					'SUMMARY'             => nl2br(TextHelper::substr_html($random_message['content'], 0, 200)),
					'AUTHOR_DISPLAY_NAME' => $random_message['login'],
					'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($random_message['level']),
					'AUTHOR_GROUP_COLOR'  => $user_group_color,

					'U_MESSAGE'        => GuestbookUrlBuilder::home($random_message['page'], $random_message['id'])->rel(),
					'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($random_message['user_id'])->rel(),
				));
			}
		}

		return $view->render();
	}
}
?>
