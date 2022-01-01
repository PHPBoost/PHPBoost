<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 6.0 - last update: 2021 03 17
 * @since   	PHPBoost 4.0 - 2016 11 25
 * @contributor xela <xela@phpboost.com>
*/

class PagesTreeLinks extends DefaultTreeLinks
{
	protected function get_module_additional_actions_tree_links(&$tree)
	{
		$module_id = 'pages';
		$request = AppContext::get_request();
		$category_id = $request->get_getstring('id_category', Category::ROOT_CATEGORY);
		$category_rewrited_name = $request->get_getstring('rewrited_name', '');
		
		if (!$request->get_getstring('id', 0) && $category_id)
			$tree->add_link(new ModuleLink(LangLoader::get_message('items.reorder', 'common', $module_id), ItemsUrlBuilder::specific_page('reorder', $module_id, $category_id != Category::ROOT_CATEGORY ? array($category_id . '-' . $category_rewrited_name) : array()), CategoriesAuthorizationsService::check_authorizations($category_id, $module_id)->moderation()));
	}
}
?>
