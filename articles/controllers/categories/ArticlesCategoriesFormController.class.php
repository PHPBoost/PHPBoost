<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2016 07 13
 * @since   	PHPBoost 4.0 - 2013 03 04
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ArticlesCategoriesFormController extends AbstractRichCategoriesFormController
{
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}

	protected function get_categories_manager()
	{
		return ArticlesService::get_categories_manager();
	}

	protected function get_categories_management_url()
	{
		return ArticlesUrlBuilder::manage_categories();
	}

	protected function get_add_category_url()
	{
		return ArticlesUrlBuilder::add_category(AppContext::get_request()->get_getint('id_parent', 0));
	}

	protected function get_edit_category_url(Category $category)
	{
		return ArticlesUrlBuilder::edit_category($category->get_id());
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
