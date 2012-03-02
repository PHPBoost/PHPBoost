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
	/**
	 * @var HTMLForm
	 */
	
	private $view;
		
	public static function get_view()
	{
		$object = new self();
		return $object->build_view();
	}
	
	public function build_view()
	{
		$request = AppContext::get_request();
		$this->init();
		
		global $LANG;
		$nbr_members_per_page = OnlineConfig::load()->get_nbr_members_per_page();
		
		//Membre connectés..
		$nbr_members_connected = OnlineService::get_nbr_users_connected("WHERE level <> -1 AND session_time > ':time'", array('time' => (time() - SessionsConfig::load()->get_active_session_duration())));
		
		$current_page = $request->get_int('page', 1);
		$nbr_pages =  ceil($nbr_members_connected / $nbr_members_per_page);
		$pagination = new Pagination($nbr_pages, $current_page);
		
		$pagination->set_url_sprintf_pattern(DispatchManager::get_url('/online', '')->absolute());
		
		$this->view->put_all(array(
			'L_LOGIN' => $LANG['pseudo'],
			'PAGINATION' => $pagination->export()->render()
		));

		$limit_page = $current_page > 0 ? $current_page : 1;
		$limit_page = (($limit_page - 1) * $nbr_members_per_page);
		
		$online_user_list = OnlineService::get_online_users_list("WHERE s.session_time > ':time' ORDER BY :display_order LIMIT " . $nbr_members_per_page . " OFFSET :start_limit", array('time' => (time() - SessionsConfig::load()->get_active_session_duration()), 'display_order' => OnlineConfig::load()->get_display_order(), 'start_limit' => $limit_page));
		
		foreach ($online_user_list as $o)
		{
			$this->view->assign_block_vars('users', array(
				'USER' => '<a href="' . UserUrlBuilder::profile($o->get_id())->absolute() . '" class="' . User::get_group_color(implode('|', $o->get_groups()), $o->get_level()) . '">' . $o->get_pseudo() . '</a>',
				'LOCATION' => '<a href="' . $o->get_location_script() . '">' . $o->get_location_title() . '</a>',
				'LAST_UPDATE' => $o->get_last_update()
			));
		}

		return $this->view;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('online_common', 'online');
		$this->view = new FileTemplate('online/OnlineHomeController.tpl');
		$this->view->add_lang($this->lang);
	}
}
?>