<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 07 10
 * @since       PHPBoost 3.0 - 2012 12 12
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GuestbookController extends DefaultModuleController
{
	private $elements_number = 0;
	private $ids = array();
	private $hide_delete_input = array();
	private $display_multiple_delete = true;

	protected function get_template_to_use()
	{
		return new FileTemplate('guestbook/GuestbookController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view();

		if ($this->display_multiple_delete)
			$this->execute_multiple_delete_if_needed($request);

		return $this->generate_response();
	}

	private function build_view()
	{
		$user_accounts_config = UserAccountsConfig::load();
		$messages_number = GuestbookService::count();
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($messages_number, $page);

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

		$delete_link_number = 0;
		while ($row = $result->fetch())
		{
			$message = new GuestbookItem();
			$message->set_properties($row);

			if ($message->is_authorized_to_delete())
			{
				$delete_link_number++;
				$this->elements_number++;
				$this->ids[$this->elements_number] = $message->get_id();
			}
			else
				$this->hide_delete_input[] = $message->get_id();

			$this->view->assign_block_vars('messages', array_merge($message->get_template_vars($page), array(
				'C_CURRENT_USER_MESSAGE' => AppContext::get_current_user()->get_display_name() == $row['login'],
				'C_AVATAR'               => $row['user_avatar'] || $user_accounts_config->is_default_avatar_enabled(),
				'C_USER_GROUPS'          => !empty($row['user_groups']),

				'MESSAGE_NUMBER' => $this->elements_number,

				'U_AVATAR' => $row['user_avatar'] ? Url::to_rel($row['user_avatar']) : $user_accounts_config->get_default_avatar()
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
						$this->view->assign_block_vars('messages.usergroups', array(
							'C_GROUP_PICTURE' => !empty($group['img']),
							'GROUP_PICTURE'   => $group['img'],
							'GROUP_NAME'      => $group['name']
						));
					}
				}
			}
		}
		$result->dispose();

		if (empty($delete_link_number))
			$this->display_multiple_delete = false;

		$this->view->put_all(array(
			'C_NO_MESSAGE'                => $result->get_rows_count() == 0,
			'C_MULTIPLE_DELETE_DISPLAYED' => $this->display_multiple_delete,
			'C_PAGINATION'                => $messages_number > GuestbookConfig::load()->get_items_per_page(),

			'PAGINATION'      => $pagination->display(),
			'MESSAGES_NUMBER' => $this->elements_number
		));

		if (GuestbookAuthorizationsService::check_authorizations()->write() && !AppContext::get_current_user()->is_readonly())
		{
			$this->view->put_all(array(
				'FORM' => GuestbookFormController::get_view(),
				'C_WRITE' => true
			));
		}
		else
		{
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['guestbook.error.post.unauthorized'], MessageHelper::WARNING));
		}

		return $this->view;
	}

	private function execute_multiple_delete_if_needed(HTTPRequestCustom $request)
	{
		if ($request->get_string('delete-selected-elements', false))
		{
			$deleted_messages_number = 0;
			for ($i = 1 ; $i <= $this->elements_number ; $i++)
			{
				if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on')
				{
					if (isset($this->ids[$i]) && !in_array($this->ids[$i], $this->hide_delete_input))
					{
						$item = GuestbookService::get_item($this->ids[$i]);
						GuestbookService::delete($item->get_id());
						HooksService::execute_hook_action('delete', self::$module_id, $item->get_properties());
						$deleted_messages_number++;
					}
				}
			}

			GuestbookCache::invalidate();
			$page = AppContext::get_request()->get_getint('page', 1);
			if ($page > 1 && $deleted_messages_number == GuestbookConfig::load()->get_items_per_page())
				$page--;

			AppContext::get_response()->redirect(GuestbookUrlBuilder::home($page), $this->lang['warning.process.success']);
		}
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
		$graphical_environment->set_page_title($this->lang['guestbook.module.title'], '', $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['guestbook.seo.description'], array('site' => GeneralConfig::load()->get_site_name())), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(GuestbookUrlBuilder::home($page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['guestbook.module.title'], GuestbookUrlBuilder::home($page));

		return $response;
	}

	public static function get_view()
	{
		$object = new self('guestbook');
		$object->check_authorizations();
		$object->build_view();
		return $object->view;
	}
}
?>
