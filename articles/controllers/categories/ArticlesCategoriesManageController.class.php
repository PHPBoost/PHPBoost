<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2016 07 13
 * @since   	PHPBoost 4.0 - 2013 03 04
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ArticlesCategoriesManageController extends AbstractCategoriesManageController
{
	protected function get_categories_manager()
	{
		return ArticlesService::get_categories_manager();
	}

	protected function get_display_category_url(Category $category)
	{
		return ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name());
	}

	protected function get_edit_category_url(Category $category)
	{
		return ArticlesUrlBuilder::edit_category($category->get_id());
	}

	protected function get_delete_category_url(Category $category)
	{
		return ArticlesUrlBuilder::delete_category($category->get_id());
	}

	protected function get_categories_management_url()
	{
		return ArticlesUrlBuilder::manage_categories();
	}

	protected function get_module_home_page_url()
	{
		return ArticlesUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('articles', 'common', 'articles');
	}

	protected function check_authorizations()
	{
		if (!ArticlesAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
