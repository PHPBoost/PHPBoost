<?php
/*##################################################
 *                        ThemesSwitcherModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : February 22, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : reidlos@phpboost.com
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
		return LangLoader::get_message('switch_theme', 'themeswitcher_common', 'ThemesSwitcher');
	}
	
	public function is_displayed()
	{
		return true;
	}
	
	public function get_menu_content()
	{
		$tpl = $this->get_content();
		
		$tpl->put('C_VERTICAL', $this->get_block() == Menu::BLOCK_POSITION__LEFT || $this->get_block() == Menu::BLOCK_POSITION__RIGHT);
		
		return $tpl->render();
	}
	
	public function get_content()
	{
		$user = AppContext::get_current_user();
		
		$theme_id = AppContext::get_request()->get_string('switchtheme', '');
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
			$query_string = preg_replace('`switchtheme=[^&]+`', '', QUERY_STRING);
			AppContext::get_response()->redirect(trim(HOST . SCRIPT . (!empty($query_string) ? '?' . $query_string : '')));
		}
		
		$tpl = new FileTemplate('ThemesSwitcher/themeswitcher.tpl');
		$tpl->add_lang(LangLoader::get('themeswitcher_common', 'ThemesSwitcher'));
		
		foreach (ThemesManager::get_activated_and_authorized_themes_map() as $id => $theme)
		{
			$selected = ($user->get_theme() == $id) ? ' selected="selected"' : '';
			$tpl->assign_block_vars('themes', array(
				'NAME' => $theme->get_configuration()->get_name(),
				'IDNAME' => $id,
				'SELECTED' => $selected
			));
		}
		
		$tpl->put('DEFAULT_THEME', UserAccountsConfig::load()->get_default_theme());
		
		return $tpl;
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
				$tpl = $this->get_content();
				
				MenuService::assign_positions_conditions($tpl, $this->get_block());
				
				return $tpl->render();
			}
		}
		return '';
	}
}
?>