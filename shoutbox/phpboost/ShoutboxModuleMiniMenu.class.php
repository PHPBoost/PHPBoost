<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 21
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ShoutboxModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function admin_display()
	{
		return '';
	}

	public function get_menu_id()
	{
		return 'module-mini-shoutbox';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('shoutbox.module.title', 'common', 'shoutbox');
	}

	public function is_displayed()
	{
		return !Url::is_current_url('/shoutbox/') && ShoutboxAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		//Create file template
		$view = new FileTemplate('shoutbox/ShoutboxModuleMiniMenu.tpl');

		//Assign the lang file to the tpl
		$view->add_lang(array_merge(LangLoader::get_all_langs('shoutbox'), LangLoader::get_module_langs('BBCode')));

		//Assign common menu variables to the tpl
		MenuService::assign_positions_conditions($view, $this->get_block());

		$config = ShoutboxConfig::load();
		$forbidden_tags = array_flip($config->get_forbidden_formatting_tags());

		if ($config->is_shout_bbcode_enabled())
		{
			$smileys_cache = SmileysCache::load();
			$smileys_per_line = 5; //Smileys par ligne.

			$smileys_displayed_number = 0;
			foreach ($smileys_cache->get_smileys() as $code_smile => $infos)
			{
				$smileys_displayed_number++;

				$view->assign_block_vars('smileys', array(
					'C_END_LINE' => $smileys_displayed_number % $smileys_per_line == 0,
					'URL' => TPL_PATH_TO_ROOT . '/images/smileys/' . $infos['url_smiley'],
					'CODE' => addslashes($code_smile)
				));
			}

			$emojis = LangLoader::get('emojis');
			foreach ($emojis as $unicode => $values)
			{
				$is_emo = TextHelper::substr($unicode, 0, 2) === "U+";
				$is_category = TextHelper::substr($unicode, 0, 7) === "U+.cat.";
				$is_sub = TextHelper::substr($unicode, 0, 7) === "U+.sub.";
				if ($is_emo)
				{
					foreach($values as $decimal => $name)
					{
						$name = TextHelper::strtolower($name);
						$name = TextHelper::ucfirst($name);
						$view->assign_block_vars('emojis', array(
							'C_CATEGORY'     => $is_category,
							'C_SUB_CATEGORY' => $is_sub,
							'C_NAME'  		 => !empty($name),

							'CATEGORY_NAME'     => $name,
							'SUB_CATEGORY_NAME' => $name,
							'NAME'              => $name,
							'DECIMAL'           => $decimal
						));
					}
				}
			}
		}

		$view->put_all(array(
			'C_MEMBER' => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
			'C_DISPLAY_FORM' => ShoutboxAuthorizationsService::check_authorizations()->write() && !AppContext::get_current_user()->is_readonly(),
			'C_VALIDATE_ONKEYPRESS_ENTER' => $config->is_validation_onkeypress_enter_enabled(),
			'C_DISPLAY_SHOUT_BBCODE' => ModulesManager::is_module_installed('BBCode') && ModulesManager::is_module_activated('BBCode') && $config->is_shout_bbcode_enabled(),
			'C_BOLD_DISABLED' => isset($forbidden_tags['b']),
			'C_ITALIC_DISABLED' => isset($forbidden_tags['i']),
			'C_UNDERLINE_DISABLED' => isset($forbidden_tags['u']),
			'C_STRIKE_DISABLED' => isset($forbidden_tags['s']),
			'C_SMILEYS_DISABLED' => isset($forbidden_tags['smileys']),
			'C_EMOJIS_DISABLED' => isset($forbidden_tags['emoji']),
			'C_AUTOMATIC_REFRESH_ENABLED' => $config->is_automatic_refresh_enabled(),
			'C_DISPLAY_NO_WRITE_AUTHORIZATION_MESSAGE' => $config->is_no_write_authorization_message_displayed(),
			'SHOUTBOX_PSEUDO' => AppContext::get_current_user()->get_display_name(),
			'SHOUT_REFRESH_DELAY' => $config->get_refresh_delay(),
			'L_ALERT_LINK_FLOOD' => sprintf(LangLoader::get_message('warning.link.flood', 'warning-lang'), $config->get_max_links_number_per_message()),
			'SHOUTBOX_MESSAGES' => ShoutboxAjaxRefreshMessagesController::get_view()
		));

		return $view->render();
	}
}
?>
