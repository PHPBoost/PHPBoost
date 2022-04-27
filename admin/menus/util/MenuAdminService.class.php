<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 02
 * @since       PHPBoost 3.0 - 2010 12 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

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
		$filter_tpl = new FileTemplate('admin/menus/filters.tpl');

		$filter_tpl->assign_block_vars('modules', array(
			'ID' => '',
		));
		foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $module)
		{
			$configuration = $module->get_configuration();
			$home_page = $configuration->get_home_page();

			if (!empty($home_page))
			{
				$filter_tpl->assign_block_vars('modules', array(
					'ID' => $module->get_id(),
				));
			}
		}

		// Add menu
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
			$regex = TextHelper::substr(TextHelper::strstr($filter_pattern, '/'), 1);

			$filter_tpl->assign_block_vars('filters', array(
				'ID' => $key,
				'FILTER' => $regex
			));

			$filter_tpl->assign_block_vars('filters.modules', array(
				'ID' => '',
				'SELECTED' => $filter_pattern == '/' ? ' selected="selected"' : ''
			));
			foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $module)
			{
				$configuration = $module->get_configuration();
				$home_page = $configuration->get_home_page();

				if (!empty($home_page))
				{
					$filter_tpl->assign_block_vars('filters.modules', array(
						'ID' => $module->get_id(),
						'NAME' => $configuration->get_name(),
						'SELECTED' => $module_name == $module->get_id() ? ' selected="selected"' : ''
					));
				}
			}
		}

		$filter_tpl->add_lang(LangLoader::get_all_langs());
		$filter_tpl->put_all(array(
		    'FILTERS_NUMBER' => ($menu->get_id() == '') ? 0 : count($menu->get_filters()) - 1,
		));

		$tpl->put('FILTERS', $filter_tpl);
	}
}
?>
