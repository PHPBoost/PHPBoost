<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 03 21
 * @since       PHPBoost 3.0 - 2012 02 22
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ThemesSwitcherModuleMiniMenu extends ModuleMiniMenu
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
		return 'module-mini-themeswitcher';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('switch.theme', 'themeswitcher_common', 'ThemesSwitcher');
	}

	public function is_displayed()
	{
		return true;
	}

	public function get_menu_content()
	{
		$user = AppContext::get_current_user();

		$theme_id = AppContext::get_request()->get_string('switchtheme', '');
		$query_string = preg_replace('`switchtheme=[^&]+`u', '', QUERY_STRING);
		if (!empty($theme_id))
		{
			$theme = ThemesManager::get_theme($theme_id);
			if ($theme !== null)
			{
				if ($theme->is_activated() && $theme->check_auth())
				{
					$user->update_theme($theme->get_id());
				}
			}
			AppContext::get_response()->redirect(trim(HOST . SCRIPT . (!empty($query_string) ? '?' . $query_string : '')));
		}

		$tpl = new FileTemplate('ThemesSwitcher/themeswitcher.tpl');
		$tpl->add_lang(LangLoader::get('themeswitcher_common', 'ThemesSwitcher'));
		MenuService::assign_positions_conditions($tpl, $this->get_block());
		Menu::assign_common_template_variables($tpl);

		foreach (ThemesManager::get_activated_and_authorized_themes_map_sorted_by_localized_name() as $theme)
		{
			$tpl->assign_block_vars('themes', array(
				'C_SELECTED' => $user->get_theme() == $theme->get_id(),
				'NAME' => $theme->get_configuration()->get_name(),
				'IDNAME' => $theme->get_id()
			));
		}

		$current_url = AppContext::get_request()->get_site_url() . $_SERVER['SCRIPT_NAME'] . '?' . rtrim($query_string, '&');

		$tpl->put_all(array(
			'DEFAULT_THEME' => UserAccountsConfig::load()->get_default_theme(),
			'URL' => $current_url . (strstr($current_url, '?') ? '&' : '?') . 'switchtheme='
		));

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
