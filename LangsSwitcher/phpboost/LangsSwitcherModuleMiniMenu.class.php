<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 03 21
 * @since       PHPBoost 3.0 - 2012 02 22
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class LangsSwitcherModuleMiniMenu extends ModuleMiniMenu
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
		return 'module-mini-langswitcher';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('switch.lang', 'langswitcher_common', 'LangsSwitcher');
	}

	public function is_displayed()
	{
		return true;
	}

	public function get_menu_content()
	{
		$user = AppContext::get_current_user();

		$lang_id = AppContext::get_request()->get_string('switchlang', '');
		$query_string = preg_replace('`switchlang=[^&]+`u', '', QUERY_STRING);
		if (!empty($lang_id))
		{
			$lang = LangsManager::get_lang($lang_id);
			if ($lang !== null)
			{
				if ($lang->is_activated() && $lang->check_auth())
				{
					$user->update_lang($lang->get_id());
				}
			}
			AppContext::get_response()->redirect(trim(HOST . SCRIPT . (!empty($query_string) ? '?' . $query_string : '')));
		}
		else
			$lang = LangsManager::get_lang($user->get_locale());

		$tpl = new FileTemplate('LangsSwitcher/langswitcher.tpl');
		$tpl->add_lang(LangLoader::get('langswitcher_common', 'LangsSwitcher'));
		MenuService::assign_positions_conditions($tpl, $this->get_block());
		Menu::assign_common_template_variables($tpl);

		$current_url = AppContext::get_request()->get_site_url() . $_SERVER['SCRIPT_NAME'] . '?' . rtrim($query_string, '&');

		$tpl->put_all(array(
			'C_HAS_PICTURE' => $lang->get_configuration()->has_picture(),
			'DEFAULT_LANG' => UserAccountsConfig::load()->get_default_lang(),
			'LANG_NAME' => $lang->get_configuration()->get_name(),
			'LANG_PICTURE_URL' => $lang->get_configuration()->get_picture_url()->rel(),
			'URL' => $current_url . (strstr($current_url, '?') ? '&' : '?') . 'switchlang='
		));

		foreach(LangsManager::get_activated_and_authorized_langs_map_sorted_by_localized_name() as $lang)
		{
			$tpl->assign_block_vars('langs', array(
				'C_SELECTED' => $user->get_locale() == $lang->get_id(),
				'NAME' => $lang->get_configuration()->get_name(),
				'IDNAME' => $lang->get_id()
			));
		}

		return $tpl->render();
	}

	public function display()
	{
		if ($this->is_displayed())
		{
			if ($this->get_block() == Menu::BLOCK_POSITION__LEFT || $this->get_block() == Menu::BLOCK_POSITION__RIGHT)
			{
				$template = $this->get_template_to_use();
				MenuService::assign_positions_conditions($template, $this->get_block());
				$this->assign_common_template_variables($template);

				$template->put_all(array(
					'ID' => $this->get_menu_id(),
					'TITLE' => $this->get_menu_title(),
					'CONTENTS' => $this->get_menu_content()
				));

				return $template->render();
			}
			else
			{
				return $this->get_menu_content();
			}
		}
		return '';
	}
}
?>
