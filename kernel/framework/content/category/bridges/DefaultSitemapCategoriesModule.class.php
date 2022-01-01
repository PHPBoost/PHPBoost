<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 6.0 - 2019 11 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultSitemapCategoriesModule extends SitemapCategoriesModule
{
	/**
	 * @var string the module identifier
	 */
	protected $module_id;

	public function __construct($module_id)
	{
		$this->module_id = $module_id;
		parent::__construct(CategoriesService::get_categories_manager($this->module_id));
	}

	protected function get_category_url(Category $category)
	{
		return CategoriesUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->module_id);
	}
}
?>
