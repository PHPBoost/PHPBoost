<?php
/*##################################################
 *                           OnlineModuleHomePage.class.php
 *                            -------------------
 *   begin                : February 08, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class OnlineModuleHomePage implements ModuleHomePage
{
	private $lang;
	private $view;
	
	public static function get_view()
	{
		$object = new self();
		return $object->build_view();
	}
	
	public function build_view()
	{
		$this->init();
		
		$this->check_authorizations();
		
		$pagination = $this->get_pagination();
		
		$this->view->put_all(array(
			'L_LOGIN' => LangLoader::get_message('pseudo', 'main'),
			'L_PAGE' => LangLoader::get_message('page', 'main'),
			'PAGINATION' => $pagination->display()
		));
		
		$users = OnlineService::get_online_users('WHERE s.session_time > :time
		ORDER BY '. OnlineConfig::load()->get_display_order_request() .'
		LIMIT :number_items_per_page OFFSET :display_from',
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from(),
				'time' => (time() - SessionsConfig::load()->get_active_session_duration())
			)
		);
		
		foreach ($users as $user)
		{
			if ($user->get_id() == AppContext::get_current_user()->get_id())
			{
				$user->set_location_script(OnlineUrlBuilder::home()->absolute());
				$user->set_location_title($this->lang['online']);
				$user->set_last_update(gmdate_format('date_format_long', time()));
			}
			
			$group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			
			if ($user->get_level() != User::VISITOR_LEVEL) 
			{
				$this->view->assign_block_vars('users', array(
					'U_PROFILE' => UserUrlBuilder::profile($user->get_id())->absolute(),
					'U_LOCATION' => $user->get_location_script(),
					'PSEUDO' => $user->get_pseudo(),
					'LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
					'C_GROUP_COLOR' => !empty($group_color),
					'GROUP_COLOR' => $group_color,
					'TITLE_LOCATION' => $user->get_location_title(),
					'LAST_UPDATE' => $user->get_last_update()
				));
			}
		}
		
		return $this->view;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('online_common', 'online');
		$this->view = new FileTemplate('online/OnlineHomeController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function check_authorizations()
	{
		if (!OnlineAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function get_pagination()
	{
		$number_users_online = PersistenceContext::get_querier()->count(
			DB_TABLE_SESSIONS, 
			'WHERE level <> -1 AND session_time > :time', 
			array(
				'time' => time() - SessionsConfig::load()->get_active_session_duration()
		));
		
		$pagination = new ModulePagination(AppContext::get_request()->get_getint('page', 1), $number_users_online, (int)OnlineConfig::load()->get_nbr_members_per_page());
		$pagination->set_url(OnlineUrlBuilder::home('%d'));
		
		if ($pagination->current_page_is_empty())
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
}
?>