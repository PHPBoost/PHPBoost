<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 01
 * @since       PHPBoost 4.0 - 2014 05 21
*/

class NewsletterStreamsFormController extends AbstractRichCategoriesFormController
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

	protected function get_add_category_url()
	{
		return NewsletterUrlBuilder::add_stream();
	}

	protected function get_edit_category_url(Category $category)
	{
		return NewsletterUrlBuilder::edit_stream($category->get_id());
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
		return $this->get_id_category() == 0 ? LangLoader::get_message('stream.add', 'common', 'newsletter') : LangLoader::get_message('stream.edit', 'common', 'newsletter');
	}

	protected function get_success_message()
	{
		return $this->is_new_category ? LangLoader::get_message('stream.message.success.add', 'common', 'newsletter') : LangLoader::get_message('stream.message.success.edit', 'common', 'newsletter');
	}

	/**
	 * @return AuthorizationsSettings
	 */
	public function get_authorizations_settings()
	{
		$lang = LangLoader::get('common', 'newsletter');

		return array(
			new ActionAuthorization($lang['auth.read'], NewsletterAuthorizationsService::AUTH_READ),
			new ActionAuthorization($lang['auth.subscribe'], NewsletterAuthorizationsService::AUTH_SUBSCRIBE),
			new ActionAuthorization($lang['auth.subscribers-read'], NewsletterAuthorizationsService::AUTH_READ_SUBSCRIBERS),
			new MemberDisabledActionAuthorization($lang['auth.subscribers-moderation'], NewsletterAuthorizationsService::AUTH_MODERATION_SUBSCRIBERS),
			new VisitorDisabledActionAuthorization($lang['auth.create-newsletter'], NewsletterAuthorizationsService::AUTH_CREATE_NEWSLETTERS),
			new ActionAuthorization($lang['auth.archives-read'], NewsletterAuthorizationsService::AUTH_READ_ARCHIVES)
		);
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
