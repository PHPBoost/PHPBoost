<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 02 05
 * @since       PHPBoost 4.1 - 2015 05 15
*/

class ForumCategoriesManagementController extends DefaultCategoriesManagementController
{
	protected function get_display_category_url(Category $category)
	{
		switch ($category->get_type())
		{
			case ForumCategory::TYPE_URL :
				$url = new Url($category->get_url());
				break;

			case ForumCategory::TYPE_FORUM :
				$url = ForumUrlBuilder::display_forum($category->get_id(), $category->get_rewrited_name());
				break;

			default :
				$url = ForumUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
				break;
		}

		return $url;
	}
}
?>
