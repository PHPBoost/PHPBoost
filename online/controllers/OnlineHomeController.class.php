<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 14
 * @since       PHPBoost 3.0 - 2012 01 29
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class OnlineHomeController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('online/OnlineHomeController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	public function build_view()
	{
		$active_sessions_start_time = time() - SessionsConfig::load()->get_active_session_duration();
		$number_users_online = OnlineService::get_number_users_connected('WHERE timestamp > :time', array('time' => $active_sessions_start_time), true);
		$pagination = $this->get_pagination($number_users_online);

		$users = OnlineService::get_online_users('WHERE s.timestamp > :time
		ORDER BY '. $this->config->get_display_order_request() .'
		LIMIT :number_items_per_page OFFSET :display_from',
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from(),
				'time' => $active_sessions_start_time
			),
			true
		);

		foreach ($users as $user)
		{
			if ($this->config->are_robots_displayed() || ($user->get_level() != User::ROBOT_LEVEL))
			{
				if ($user->get_id() == AppContext::get_current_user()->get_id())
				{
					$user->set_location_script(OnlineUrlBuilder::home()->rel());
					$user->set_location_title($this->lang['online.module.title']);
					$user->set_last_update(new Date());
				}

				$group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

				$this->view->assign_block_vars('items', array_merge(
					Date::get_array_tpl_vars($user->get_last_update(), 'date'),
					array(
					'C_AVATAR'      => $user->has_avatar(),
					'C_GROUP_COLOR' => !empty($group_color),
					'C_ROBOT'       => $user->get_level() == User::ROBOT_LEVEL,

					'PSEUDO'         => $user->get_display_name(),
					'LEVEL'          => UserService::get_level_lang($user->get_level()),
					'LEVEL_CLASS'    => UserService::get_level_class($user->get_level()),
					'GROUP_COLOR'    => $group_color,
					'LOCATION_TITLE' => $user->get_location_title(),

					'U_PROFILE'  => UserUrlBuilder::profile($user->get_id())->rel(),
					'U_LOCATION' => $user->get_location_script(),
					'U_AVATAR'   => $user->get_avatar()
					)
				));
			}
		}

		$this->view->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_USERS' => count($users),
			'PAGINATION' => $pagination->display()
		));

		return $this->view;
	}

	private function check_authorizations()
	{
		if (!OnlineAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function get_pagination($number_users_online)
	{
		$page = AppContext::get_request()->get_getint('page', 1);
		$pagination = new ModulePagination($page, $number_users_online, (int)$this->config->get_number_members_per_page());
		$pagination->set_url(OnlineUrlBuilder::home('%d'));

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
		$graphical_environment->set_page_title($this->lang['online.module.title'], '', $page);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['online.seo.description'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(OnlineUrlBuilder::home($page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['online.module.title'], OnlineUrlBuilder::home($page));

		return $response;
	}

	public static function get_view()
	{
		$object = new self('online');
		$object->check_authorizations();
		$object->build_view();
		return $object->view;
	}
}
?>
