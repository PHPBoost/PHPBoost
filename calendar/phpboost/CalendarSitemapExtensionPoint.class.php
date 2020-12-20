<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 20
 * @since       PHPBoost 4.0 - 2013 02 27
*/

class CalendarSitemapExtensionPoint extends SitemapCategoriesModule
{
	public function __construct()
	{
		parent::__construct(CategoriesService::get_categories_manager('calendar'));
	}

	protected function get_category_url(Category $category)
	{
		return CalendarUrlBuilder::u_home();
	}
}
?>
