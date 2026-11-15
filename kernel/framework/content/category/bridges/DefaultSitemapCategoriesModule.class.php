<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 05
 * @since       PHPBoost 6.0 - 2019 11 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultSitemapCategoriesModule extends SitemapCategoriesModule
{
	protected string $module_id;

	public function __construct(string $module_id)
	{
		$this->module_id = $module_id;
		parent::__construct(CategoriesService::get_categories_manager($this->module_id));
	}

	protected function get_category_url(Category $category): mixed
	{
		return CategoriesUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->module_id);
	}
}
?>
