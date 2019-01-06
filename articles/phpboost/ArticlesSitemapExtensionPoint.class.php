<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2014 12 22
 * @since   	PHPBoost 4.0 - 2013 03 05
*/

class ArticlesSitemapExtensionPoint extends SitemapCategoriesModule
{
	public function __construct()
	{
		parent::__construct(ArticlesService::get_categories_manager());
	}

	protected function get_category_url(Category $category)
	{
		return ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}
}
?>
