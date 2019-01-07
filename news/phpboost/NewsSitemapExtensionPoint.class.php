<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 31 24
 * @since   	PHPBoost 4.0 - 2013 02 26
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class NewsSitemapExtensionPoint extends SitemapCategoriesModule
{
	public function __construct()
	{
		parent::__construct(NewsService::get_categories_manager());
	}

	protected function get_category_url(Category $category)
	{
		return NewsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}
}
?>
