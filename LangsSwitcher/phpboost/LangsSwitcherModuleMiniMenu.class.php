<?php
/*##################################################
 *                        LangsSwitcherModuleMiniMenu.class.php
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
		return LangLoader::get_message('switch_lang', 'langswitcher_common', 'LangsSwitcher');
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

		$lang_id = AppContext::get_request()->get_string('switchlang', '');
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
			$query_string = preg_replace('`switchlang=[^&]+`', '', QUERY_STRING);
			AppContext::get_response()->redirect(trim(HOST . SCRIPT . (!empty($query_string) ? '?' . $query_string : '')));
		}

		$tpl = new FileTemplate('LangsSwitcher/langswitcher.tpl');
		$tpl->add_lang(LangLoader::get('langswitcher_common', 'LangsSwitcher'));
		
		foreach(LangsManager::get_activated_and_authorized_langs_map() as $id => $lang)
		{
			$selected = ($user->get_locale() == $id) ? ' selected="selected"' : '';
			$tpl->assign_block_vars('langs', array(
				'NAME' => $lang->get_configuration()->get_name(),
				'IDNAME' => $id,
				'SELECTED' => $selected
			));
		}
		
		$lang_identifier = str_replace('en', 'uk', LangLoader::get_message('xml_lang', 'main'));
		$tpl->put_all(array(
			'DEFAULT_LANG' => UserAccountsConfig::load()->get_default_lang(),
			'IMG_LANG_IDENTIFIER' => TPL_PATH_TO_ROOT . '/images/stats/countries/' . $lang_identifier . '.png',
		));
		
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