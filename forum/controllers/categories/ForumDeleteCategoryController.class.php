<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 07 15
 * @since       PHPBoost 4.1 - 2015 05 15
*/

class ForumDeleteCategoryController extends DefaultDeleteCategoryController
{
	protected function get_categories_manager()
	{
		return CategoriesService::get_categories_manager('forum', 'idcat');
	}
}
?>
