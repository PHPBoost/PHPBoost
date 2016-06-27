<?php
/*##################################################
 *                          ModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : November 15, 2008
 *   copyright            : (C) 2008 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

/**
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc
 * @package {@package}
 */
class ModuleMiniMenu extends Menu
{
	const MODULE_MINI_MENU__CLASS = 'ModuleMiniMenu';

	/**
	 * @desc Build a ModuleMiniMenu element.
	 */
	public function __construct()
	{
		parent::__construct($this->get_formated_title());
	}
	
	public function get_menu_id()
	{
		return '';
	}
	
	public function get_menu_title()
	{
		return '';
	}
	
	public function get_menu_content()
	{
		return '';
	}
	
	public function is_displayed()
	{
		return true;
	}
	
	public function get_formated_title()
	{
		$class_name = get_class($this);
		$module_name = strstr($class_name, self::MODULE_MINI_MENU__CLASS, true);
		$module_name = strlen(preg_replace('/[a-z]*/', '', $module_name)) > 1 ? $module_name : strtolower($module_name); //Pour les modules qui ont plus de 2 majuscules on les garde, sinon on les enlève
		
		$module = ModulesManager::get_module($module_name);
		
		if (empty($module))
		{
			foreach (ModulesManager::get_activated_modules_map() as $activated_module)
			{
				if (strstr(strtolower($module_name), strtolower($activated_module->get_id())))
					$module = $activated_module;
			}
		}
		
		$localized_module_name = !empty($module) ? $module->get_configuration()->get_name() : '';
		
		return !empty($localized_module_name) ? (!preg_match('/^' . Langloader::get_message('admin.main_menu', 'main') . ' /', $localized_module_name) ? Langloader::get_message('admin.main_menu', 'main') . ' ' : '') . $localized_module_name : $class_name;
	}
	
	public function display()
	{
		$filters = $this->get_filters();
		$is_displayed = empty($filters) || $filters[0]->get_pattern() == '/';
		
		foreach ($filters as $key => $filter) 
		{
			if ($filter->get_pattern() != '/' && $filter->match())
			{
				$is_displayed = true;
				break;
			}
		}
		
		if ($is_displayed && $this->is_displayed())
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
		return '';
	}
	
	public function get_default_block()
	{
		return self::BLOCK_POSITION__NOT_ENABLED;
	}
	
	public function default_is_enabled() { return false; }

	protected function get_default_template()
	{
		return new FileTemplate('framework/menus/modules_mini.tpl');
	}
}
?>