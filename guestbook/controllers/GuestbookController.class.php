<?php
/*##################################################
 *                      GuestbookController.class.php
 *                            -------------------
 *   begin                : December 12, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
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
class GuestbookController extends ModuleController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	private function build_view()
	{
		$user_accounts_config = UserAccountsConfig::load();
		$messages_number = GuestbookService::count();
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($messages_number, $page);
		$is_guest = !AppContext::get_current_user()->check_level(User::MEMBER_LEVEL);
		
		$result = PersistenceContext::get_querier()->select('SELECT member.*, guestbook.*, guestbook.login as glogin, ext_field.user_avatar
		FROM ' . GuestbookSetup::$guestbook_table . ' guestbook
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = guestbook.user_id
		LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = member.user_id
		ORDER BY guestbook.timestamp DESC
		LIMIT :number_items_per_page OFFSET :display_from',
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);
		
		while ($row = $result->fetch())
		{
			$message = new GuestbookMessage();
			$message->set_properties($row);
			
			//Avatar
			$user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : ($user_accounts_config->is_default_avatar_enabled() ? Url::to_rel('/templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '');
			
			$this->view->assign_block_vars('messages', array_merge($message->get_array_tpl_vars($page), array(
				'C_AVATAR' => $row['user_avatar'] || ($user_accounts_config->is_default_avatar_enabled()),
				'C_USER_GROUPS' => !empty($row['groups']),
				'U_AVATAR' => $user_avatar
			)));
			
			//user's groups
			if ($message->get_author_user()->get_groups())
			{
				$groups_cache = GroupsCache::load();
				$user_groups = $message->get_author_user()->get_groups();
				foreach ($user_groups as $user_group_id)
				{
					if ($groups_cache->group_exists($user_group_id))
					{
						$group = $groups_cache->get_group($user_group_id);
						$this->view->assign_block_vars('messages.user_groups', array(
							'C_GROUP_PICTURE' => !empty($group['img']),
							'GROUP_PICTURE' => $group['img'],
							'GROUP_NAME' => $group['name']
						));
					}
				}
			}
		}
		$result->dispose();
		
		$this->view->put_all(array(
			'C_NO_MESSAGE' => $result->get_rows_count() == 0,
			'C_PAGINATION' => $messages_number > GuestbookConfig::load()->get_items_per_page(),
			'PAGINATION' => $pagination->display()
		));
		
		if (GuestbookAuthorizationsService::check_authorizations()->write() && !AppContext::get_current_user()->is_readonly())
		{
			$this->view->put('FORM', GuestbookFormController::get_view());
		}
		else
		{
			$this->view->put('MSG', MessageHelper::display($this->lang['error.post.unauthorized'], MessageHelper::WARNING));
		}
		 
		return $this->view;
	}
	
	private function init()
	{
		$this->current_user = AppContext::get_current_user();
		
		$this->lang = LangLoader::get('common', 'guestbook');
		$this->view = new FileTemplate('guestbook/GuestbookController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function check_authorizations()
	{
		if (!GuestbookAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function get_pagination($messages_number, $page)
	{
		$pagination = new ModulePagination($page, $messages_number, (int)GuestbookConfig::load()->get_items_per_page());
		$pagination->set_url(GuestbookUrlBuilder::home('%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function generate_response()
	{
		$page = AppContext::get_request()->get_getint('page', 1);
		
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(GuestbookUrlBuilder::home($page));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], GuestbookUrlBuilder::home($page));
		
		return $response;
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->check_authorizations();
		$object->init();
		$object->build_view();
		return $object->view;
	}
}
?>
