<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2019 01 18
 * @since   	PHPBoost 5.2 - 2019 01 18
*/

class ForumSitemapExtensionPoint extends SitemapCategoriesModule
{
	public function __construct()
	{
		parent::__construct(ForumService::get_categories_manager());
	}

	protected function get_category_url(Category $category)
	{
		return ForumUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}
}
?>
