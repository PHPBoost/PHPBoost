<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2016 11 25
 * @author      xela <xela@phpboost.com>
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
			// Reorder is an admin action — keep /pages/ prefix via PagesUrlBuilder::reorder_items()
			$tree->add_link(new ModuleLink(
				LangLoader::get_message('items.reorder', 'common', $module_id),
				PagesUrlBuilder::reorder_items(
					$category_id != Category::ROOT_CATEGORY ? (int)$category_id : 0,
					$category_id != Category::ROOT_CATEGORY ? $category_rewrited_name : ''
				),
				CategoriesAuthorizationsService::check_authorizations($category_id, $module_id)->moderation()
			));
	}
}
?>
