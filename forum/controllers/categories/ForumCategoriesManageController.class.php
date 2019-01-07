<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 07 15
 * @since   	PHPBoost 4.1 - 2015 05 15
*/

class ForumCategoriesManageController extends AbstractCategoriesManageController
{
	protected function get_categories_manager()
	{
		return ForumService::get_categories_manager();
	}

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

	protected function get_edit_category_url(Category $category)
	{
		return ForumUrlBuilder::edit_category($category->get_id());
	}

	protected function get_delete_category_url(Category $category)
	{
		return ForumUrlBuilder::delete_category($category->get_id());
	}

	protected function get_categories_management_url()
	{
		return ForumUrlBuilder::manage_categories();
	}

	protected function get_module_home_page_url()
	{
		return ForumUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('module_title', 'common', 'forum');
	}

	protected function check_authorizations()
	{
		if (!ForumAuthorizationsService::check_authorizations()->manage_categories())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
