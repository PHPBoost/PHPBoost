<?php
/*##################################################
 *		                NewsletterStreamsFormController.class.php
 *                            -------------------
 *   begin                : May 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
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
		
		return new AuthorizationsSettings(array(
			new ActionAuthorization($lang['auth.read'], NewsletterAuthorizationsService::AUTH_READ),
			new ActionAuthorization($lang['auth.subscribe'], NewsletterAuthorizationsService::AUTH_SUBSCRIBE),
			new ActionAuthorization($lang['auth.subscribers-read'], NewsletterAuthorizationsService::AUTH_READ_SUBSCRIBERS),
			new ActionAuthorization($lang['auth.subscribers-moderation'], NewsletterAuthorizationsService::AUTH_MODERATION_SUBSCRIBERS),
			new ActionAuthorization($lang['auth.create-newsletter'], NewsletterAuthorizationsService::AUTH_CREATE_NEWSLETTERS),
			new ActionAuthorization($lang['auth.archives-read'], NewsletterAuthorizationsService::AUTH_READ_ARCHIVES)
		));
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