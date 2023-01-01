<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 14
 * @since       PHPBoost 4.0 - 2014 05 21
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsletterStreamsManagementController extends AbstractCategoriesManagementController
{
	protected static function get_categories_manager()
	{
		return NewsletterService::get_streams_manager();
	}

	protected function get_display_category_url(Category $category)
	{
		return NewsletterUrlBuilder::archives($category->get_id(), $category->get_rewrited_name());
	}

	protected function get_edit_category_url(Category $category)
	{
		return NewsletterUrlBuilder::edit_stream($category->get_id());
	}

	protected function get_delete_category_url(Category $category)
	{
		return NewsletterUrlBuilder::delete_stream($category->get_id());
	}

	protected function get_categories_management_url()
	{
		return NewsletterUrlBuilder::manage_streams();
	}

	protected function get_module_home_page_url()
	{
		return NewsletterUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('newsletter.module.title', 'common', 'newsletter');
	}

	protected function get_title()
	{
		return LangLoader::get_message('newsletter.streams.management', 'common', 'newsletter');
	}

	protected function get_delete_confirmation_message()
	{
		return LangLoader::get_message('newsletter.stream.delete.confirmation', 'common', 'newsletter');
	}

	protected function check_authorizations()
	{
		if (!NewsletterAuthorizationsService::default_authorizations()->manage_streams())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
