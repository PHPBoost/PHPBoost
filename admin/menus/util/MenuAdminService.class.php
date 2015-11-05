<?php
/*##################################################
 *                                 MenuAdminService.class.php
 *                            -------------------
 *   begin                : December, 29 2010
 *   copyright            : (C) 2010 Régis Viarre
 *   email                : crowkait@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class MenuAdminService
{
	public static function set_retrieved_filters(Menu $menu) 
	{
		$request = AppContext::get_request();
	    $filters = array();
	    $i = 0;
	    while (true) 
	    {
	    	if (!$request->has_postparameter('filter_module' . $i)) 
	    	{
	    		break;
	    	}
	    	
	    	$filter_module = $request->get_poststring('filter_module' . $i);
	    	$filter_regex = trim($request->get_poststring('f' . $i), '/');
	    	if ($filter_regex != '_deleted') 
	    	{
	    		$filters[] = new MenuStringFilter($filter_module . '/' . $filter_regex);
	    	}
	    	
	    	$i++;
	    }
	    if (empty($filters)) {
	    	$filters[] = new MenuStringFilter('/');
	    }
	    $menu->set_filters($filters);
	}
	
	public static function add_filter_fieldset(Menu $menu, Template $tpl) 
	{
		$tpl_filter = new FileTemplate('admin/menus/filters.tpl');
		
		$tpl_filter->assign_block_vars('modules', array(
			'ID' => '',
		));
		foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $module)
		{
			$configuration = $module->get_configuration();
			$home_page = $configuration->get_home_page();
			
			if (!empty($home_page))
			{
				$tpl_filter->assign_block_vars('modules', array(
					'ID' => $module->get_id(),
				));
			}
		}
		
		//Ajout du menu
		if ($menu->get_id() == '')
		{
			$menu->set_filters(array(new MenuStringFilter('/')));
		}
		
		// Installed modules
		foreach ($menu->get_filters() as $key => $filter) 
		{
			$filter_pattern = $filter->get_pattern();
			
			$filter_infos = explode('/', $filter_pattern);
			$module_name = $filter_infos[0];
			$regex = substr(strstr($filter_pattern, '/'), 1);
		
			$tpl_filter->assign_block_vars('filters', array(
				'ID' => $key,
				'FILTER' => $regex
			));
				
			$tpl_filter->assign_block_vars('filters.modules', array(
				'ID' => '',
				'SELECTED' => $filter_pattern == '/' ? ' selected="selected"' : ''
			));
			foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $module)
			{
				$configuration = $module->get_configuration();
				$home_page = $configuration->get_home_page();
			
				if (!empty($home_page))
				{
					$tpl_filter->assign_block_vars('filters.modules', array(
						'ID' => $module->get_id(),
						'SELECTED' => $module_name == $module->get_id() ? ' selected="selected"' : ''
					));
				}
			}
		}
		
		$tpl_filter->add_lang(LangLoader::get('admin-menus-common'));
		$tpl_filter->put_all(array(
		    'NBR_FILTER' => ($menu->get_id() == '') ? 0 : count($menu->get_filters()) - 1,
		));
		
		$tpl->put('filters', $tpl_filter);
	}
}
?>
