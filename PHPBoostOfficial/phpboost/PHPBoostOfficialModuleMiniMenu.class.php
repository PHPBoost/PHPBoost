<?php
/*##################################################
 *                               PHPBoostOfficialModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : May 19, 2017
 *   copyright            : (C) 2017 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class PHPBoostOfficialModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}
	
	public function get_menu_id()
	{
		return 'module-mini-phpboostofficial';
	}
	
	public function get_menu_title()
	{
		return LangLoader::get_message('menu_title', 'common', 'PHPBoostOfficial');
	}
	
	public function get_menu_content()
	{
		//Create file template
		$tpl = new FileTemplate('PHPBoostOfficial/PHPBoostOfficialModuleMiniMenu.tpl');
		
		//Assign the lang file to the tpl
		$tpl->add_lang(LangLoader::get('common', 'PHPBoostOfficial'));
		
		//Assign common menu variables to the tpl
		MenuService::assign_positions_conditions($tpl, $this->get_block());
		
		//Load module cache
		$cache = PHPBoostOfficialCache::load();
		
		foreach ($cache->get_last_modules() as $module)
		{
			$tpl->assign_block_vars('modules', array(
				'U_LINK' => $module['link'],
				'U_IMG' => $module['picture'],
				'C_IMG' => !empty($module['picture']),
				'TITLE' => $module['title'],
				'DESC' => $module['description'],
				'VERSION' => $module['version'],
				'PSEUDO' => $module['author']
			));
		}
		
		foreach ($cache->get_last_themes() as $theme)
		{
			$tpl->assign_block_vars('themes', array(
				'U_LINK' => $theme['link'],
				'U_IMG' => $theme['picture'],
				'C_IMG' => !empty($theme['picture']),
				'TITLE' => $theme['title'],
				'DESC' => $theme['description'],
				'VERSION' => $theme['version'],
				'PSEUDO' => $theme['author']
			));
		}
		
		$tpl->assign_block_vars('last_news', $cache->get_last_news());
		
		return $tpl->render();
	}
}
?>
