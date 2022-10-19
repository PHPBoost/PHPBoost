<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 18
 * @since       PHPBoost 4.1 - 2014 10 14
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

class ShoutboxHomeController extends DefaultModuleController
{
	private $elements_number = 0;
	private $ids = array();
	private $hide_delete_input = array();
	private $display_multiple_delete = true;

	protected function get_template_to_use()
	{
		return new FileTemplate('shoutbox/ShoutboxHomeController.tpl');
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
		$messages_number = ShoutboxService::count();
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = $this->get_pagination($messages_number, $page);
		$is_guest = !AppContext::get_current_user()->check_level(User::MEMBER_LEVEL);

		$result = PersistenceContext::get_querier()->select('SELECT member.*, shoutbox.*, ext_field.user_avatar
		FROM ' . ShoutboxSetup::$shoutbox_table . ' shoutbox
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = shoutbox.user_id
		LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = member.user_id
		ORDER BY shoutbox.timestamp DESC
		LIMIT :number_items_per_page OFFSET :display_from',
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);

		$delete_link_number = 0;
		while ($row = $result->fetch())
		{
			$message = new ShoutboxItem();
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
				'C_USER_GROUPS'          => array_filter($message->get_author_user()->get_groups()),
				'MESSAGE_NUMBER'         => $this->elements_number,
				'U_AVATAR'               => $row['user_avatar'] ? Url::to_rel($row['user_avatar']) : $user_accounts_config->get_default_avatar()
			)));

			// user's groups
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
							'GROUP_NAME'      => $group['name'],
							'GROUP_ID'        => $user_group_id
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
			'C_PAGINATION'                => $messages_number > ShoutboxConfig::load()->get_items_per_page(),
			'PAGINATION'                  => $pagination->display(),
			'MESSAGES_NUMBER'             => $this->elements_number
		));

		if (ShoutboxAuthorizationsService::check_authorizations()->write() && !AppContext::get_current_user()->is_readonly())
		{
			$this->view->put_all(array(
				'FORM' => ShoutboxFormController::get_view(),
				'C_WRITE' => true
			));
		}
		else
		{
			$forbidden_to_write = ShoutboxConfig::load()->is_no_write_authorization_message_displayed();
			if ($forbidden_to_write)
				$this->view->put_all(array(
					'C_FORBIDDEN_TO_WRITE' => $forbidden_to_write,
					'MESSAGE_HELPER' => MessageHelper::display($this->lang['shoutbox.error.post.unauthorized'], MessageHelper::WARNING)
				));
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
						$item = ShoutboxService::get_item($this->ids[$i]);
						ShoutboxService::delete($item->get_id());
						HooksService::execute_hook_action('delete', self::$module_id, $item->get_properties());
						$deleted_messages_number++;
					}
				}
			}

			$page = AppContext::get_request()->get_getint('page', 1);
			if ($page > 1 && $deleted_messages_number == ShoutboxConfig::load()->get_items_per_page())
				$page--;

			AppContext::get_response()->redirect(ShoutboxUrlBuilder::home($page), LangLoader::get_message('warning.process.success', 'warning-lang'));
		}
	}

	private function check_authorizations()
	{
		if (!ShoutboxAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function get_pagination($messages_number, $page)
	{
		$pagination = new ModulePagination($page, $messages_number, (int)ShoutboxConfig::load()->get_items_per_page());
		$pagination->set_url(ShoutboxUrlBuilder::home('%d'));

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
		$graphical_environment->set_page_title($this->lang['shoutbox.module.title'], '', $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['shoutbox.seo.description'], array('site' => GeneralConfig::load()->get_site_name())), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ShoutboxUrlBuilder::home($page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['shoutbox.module.title'], ShoutboxUrlBuilder::home($page));

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
