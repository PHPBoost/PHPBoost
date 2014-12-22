<?php
/*##################################################
 *                      UserUsersListController.class.php
 *                            -------------------
 *   begin                : October 09, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class UserUsersListController extends AbstractController
{
	private $lang;
	private $view;
	private $groups_cache;
	private $nbr_members_per_page = 25;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_select_group_form();
		$this->build_view($request);

		return $this->build_response($this->view);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('user-common');
		$this->view = new FileTemplate('user/UserUsersListController.tpl');
		$this->view->add_lang($this->lang);
		$this->groups_cache = GroupsCache::load();
	}

	private function build_view($request)
	{
		$field = $request->get_value('field', 'registered');
		$sort = $request->get_value('sort', 'top');
		$page = $request->get_int('page', 1);
		
		$mode = ($sort == 'top') ? 'ASC' : 'DESC';
		
		switch ($field)
		{
			case 'registered' :
				$field_bdd = 'm.registration_date';
			break;
			case 'connect' :
				$field_bdd = 'm.last_connection_date';
			break;
			case 'messages' :
				$field_bdd = 'm.posted_msg';
			break;
			case 'login' :
				$field_bdd = 'm.display_name';
			break;
			default :
				$field_bdd = 'm.registration_date';
		}
		
		$pagination = $this->get_pagination($page, $field, $sort);
		
		$this->view->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'SORT_LOGIN_TOP' => UserUrlBuilder::users('login' ,'top', $page)->rel(),
			'SORT_LOGIN_BOTTOM' => UserUrlBuilder::users('login', 'bottom', $page)->rel(),
			'SORT_REGISTERED_TOP' => UserUrlBuilder::users('registered', 'top'. $page)->rel(),
			'SORT_REGISTERED_BOTTOM' => UserUrlBuilder::users('registered', 'bottom', $page)->rel(),
			'SORT_MSG_TOP' => UserUrlBuilder::users('messages', 'top', $page)->rel(),
			'SORT_MSG_BOTTOM' => UserUrlBuilder::users('messages', 'bottom', $page)->rel(),
			'SORT_LAST_CONNECT_TOP' => UserUrlBuilder::users('connect', 'top', $page)->rel(),
			'SORT_LAST_CONNECT_BOTTOM' => UserUrlBuilder::users('connect', 'bottom', $page)->rel(),
			'PAGINATION' => $pagination->display()
		));
		
		$result = PersistenceContext::get_querier()->select("SELECT m.user_id, m.display_name, m.email, m.show_email, m.registration_date, m.last_connection_date, m.level, m.groups, m.posted_msg, 
		ia.approved
		FROM " . DB_TABLE_MEMBER . " m
		LEFT JOIN " . DB_TABLE_INTERNAL_AUTHENTICATION ." ia ON ia.user_id = m.user_id
		ORDER BY ". $field_bdd ." ". $mode ."
		LIMIT :number_items_per_page OFFSET :display_from", array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		));
		
		while ($row = $result->fetch())
		{
			$posted_msg = !empty($row['posted_msg']) ? $row['posted_msg'] : '0';
			$group_color = User::get_group_color($row['groups'], $row['level']);
			
			$this->view->assign_block_vars('member_list', array(
				'C_MAIL' => $row['show_email'] == 1,
				'C_GROUP_COLOR' => !empty($group_color),
				'PSEUDO' => $row['display_name'],
				'LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'GROUP_COLOR' => $group_color,
				'MAIL' => $row['email'],
				'MSG' => $posted_msg,
				'LAST_CONNECT' => !empty($row['last_connection_date']) ? gmdate_format('date_format_short', $row['last_connection_date']) : LangLoader::get_message('never', 'main'),
				'DATE' => gmdate_format('date_format_short', $row['registration_date']),
				'U_USER_ID' => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_USER_PM' => UserUrlBuilder::personnal_message($row['user_id'])->rel()
			));
		}
	}
	
	private function build_select_group_form()
	{
		$form = new HTMLForm('groups', '', false);

		$fieldset = new FormFieldsetHorizontal('show_group');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('groups_select', $this->lang['groups.select'] . ' : ', '', $this->build_select_groups(), 
			array('events' => array('change' => 'document.location = "'. UserUrlBuilder::groups()->rel() .'" + HTMLForms.getField("groups_select").getValue();')
		)));

		$groups = $this->groups_cache->get_groups();
		$this->view->put_all(array(
			'C_ARE_GROUPS' => !empty($groups),
			'SELECT_GROUP' => $form->display()
		));
	}
	
	private function get_pagination($page, $field, $sort)
	{
		$number_members = PersistenceContext::get_querier()->count(DB_TABLE_MEMBER);
		
		$pagination = new ModulePagination($page, $number_members, $this->nbr_members_per_page);
		$pagination->set_url(UserUrlBuilder::users($field, $sort, '%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function build_select_groups()
	{
		$groups = array();
		$list_lang = LangLoader::get_message('list', 'main');
		$groups[] = new FormFieldSelectChoiceOption('-- '. $list_lang .' --', '');
		foreach ($this->groups_cache->get_groups() as $id => $row)
		{
			$groups[] = new FormFieldSelectChoiceOption($row['name'], $id);
		}
		return $groups;
	}

	private function build_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['users']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['users'], UserUrlBuilder::users()->rel());
		
		return $response;
	}
	
	public function get_right_controller_regarding_authorizations()
	{
		if (!AppContext::get_current_user()->check_auth(UserAccountsConfig::load()->get_auth_read_members(), UserAccountsConfig::AUTH_READ_MEMBERS_BIT))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		return $this;
	}
}
?>