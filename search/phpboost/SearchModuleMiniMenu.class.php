<?php
/*##################################################
 *                          SearchModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : October 08, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class SearchModuleMiniMenu extends ModuleMiniMenu
{    
	public function get_default_block()
	{
		return self::BLOCK_POSITION__HEADER;
	}
	
	public function admin_display()
	{
		return '';
	}
	
	public function get_menu_id()
	{
		return 'module-mini-search-form';
	}
	
	public function get_menu_title()
	{
		global $LANG;
		load_module_lang('search');
		return $LANG['title_search'];
	}
	
	public function is_displayed()
	{
		return SearchAuthorizationsService::check_authorizations()->read();
	}
	
	public function get_menu_content()
	{
		$tpl = $this->get_content();
		
		$tpl->put('C_VERTICAL', $this->get_block() == Menu::BLOCK_POSITION__LEFT || $this->get_block() == Menu::BLOCK_POSITION__RIGHT);
		
		return $tpl->render();
	}
	
	public function get_content()
	{
		global $LANG;
		load_module_lang('search');
		
		$search = retrieve(REQUEST, 'q', '');
		
		$tpl = new FileTemplate('search/search_mini.tpl');
		
		$tpl->put_all(Array(
			'TEXT_SEARCHED' => !empty($search) ? stripslashes($search) : '',
			'WARNING_LENGTH_STRING_SEARCH' => addslashes($LANG['warning_length_string_searched']),
			'L_SEARCH' => $LANG['search'],
			'U_FORM_VALID' => url(TPL_PATH_TO_ROOT . '/search/search.php#results'),
			'L_ADVANCED_SEARCH' => $LANG['advanced_search'],
			'U_ADVANCED_SEARCH' => url(TPL_PATH_TO_ROOT . '/search/search.php')
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
				$this->assign_common_template_variables($tpl);
				
				return $tpl->render();
			}
		}
		return '';
	}
}
?>