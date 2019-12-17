<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 07 14
 * @since       PHPBoost 4.0 - 2014 05 21
*/

class NewsletterDeleteStreamController extends AbstractDeleteCategoryController
{
	protected function get_id_category()
	{
		return AppContext::get_request()->get_getint('id', 0);
	}

	protected function get_categories_manager()
	{
		return NewsletterService::get_streams_manager();
	}

	protected function get_categories_management_url()
	{
		return NewsletterUrlBuilder::manage_streams();
	}

	protected function get_delete_category_url(Category $category)
	{
		return NewsletterUrlBuilder::delete_stream($category->get_id());
	}

	protected function get_module_home_page_url()
	{
		return NewsletterUrlBuilder::home();
	}

	protected function get_module_home_page_title()
	{
		return LangLoader::get_message('newsletter', 'common', 'newsletter');
	}

	protected function get_title()
	{
		return LangLoader::get_message('stream.delete', 'common', 'newsletter');
	}

	protected function get_description()
	{
		return LangLoader::get_message('stream.delete.description', 'common', 'newsletter');
	}

	protected function get_success_message()
	{
		return LangLoader::get_message('stream.message.success.delete', 'common', 'newsletter');
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
