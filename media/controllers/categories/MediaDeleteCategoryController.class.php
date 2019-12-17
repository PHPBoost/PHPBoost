<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 07
 * @since       PHPBoost 4.1 - 2015 02 04
*/

class MediaDeleteCategoryController extends DefaultDeleteCategoryController
{
	protected function get_categories_manager()
	{
		return CategoriesService::get_categories_manager('media', 'idcat');
	}
}
?>
