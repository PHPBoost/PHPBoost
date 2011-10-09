<?php
/*##################################################
 *                      UserUsersListController.class.php
 *                            -------------------
 *   begin                : October 09, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
	private $nbr_members_per_page = 25;

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form($request);

		return $this->build_response($this->view);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('user-common');
		$this->view = new FileTemplate('user/UserUsersListController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_form($request)
	{
		$field = $request->get_value('field', 'login');
		$sort = $request->get_value('sort', 'top');
		$page = $request->get_int('page', 1);
		
		$mode = ($sort == 'top') ? 'ASC' : 'DESC';
		
		switch ($field)
		{
			case 'registered' :
				$field_bdd = 'timestamp';
			break;
			case 'connect' :
				$field_bdd = 'last_connect';
			break;
			case 'messages' :
				$field_bdd = 'user_msg';
			break;
			case 'login' :
				$field_bdd = 'login';
			break;
			default :
				$field_bdd = 'timestamp';
		}
		
		$pagination = new UserUsersListPagination($page);
		$pagination->set_url($field, $sort);
		$this->view->put_all(array(
			'SORT_LOGIN_TOP' => UserUrlBuilder::users('login' ,'top', $page)->absolute(),
			'SORT_LOGIN_BOTTOM' => UserUrlBuilder::users('login', 'bottom', $page)->absolute(),
			'SORT_REGISTERED_TOP' => UserUrlBuilder::users('registered', 'top'. $page)->absolute(),
			'SORT_REGISTERED_BOTTOM' => UserUrlBuilder::users('registered', 'bottom', $page)->absolute(),
			'SORT_MSG_TOP' => UserUrlBuilder::users('messages', 'top', $page)->absolute(),
			'SORT_MSG_BOTTOM' => UserUrlBuilder::users('messages', 'bottom', $page)->absolute(),
			'SORT_LAST_CONNECT_TOP' => UserUrlBuilder::users('connect', 'top', $page)->absolute(),
			'SORT_LAST_CONNECT_BOTTOM' => UserUrlBuilder::users('connect', 'bottom', $page)->absolute(),
			'PAGINATION' => '&nbsp;<strong>' . LangLoader::get_message('page', 'main') . ' :</strong> ' . $pagination->display()->render()
		));

		$condition = 'WHERE user_aprob = 1 ORDER BY :field :sort LIMIT :number_users_per_page OFFSET :display_from';
		$parameters = array(
			'field' => $field_bdd,
			'sort' => $mode,
			'number_users_per_page' => $pagination->get_number_users_per_page(),
			'display_from' => $pagination->get_display_from()
		);
		$result = PersistenceContext::get_querier()->select_rows(DB_TABLE_MEMBER, array('*'), $condition, $parameters);
		while ($row = $result->fetch())
		{
			$user_msg = !empty($row['user_msg']) ? $row['user_msg'] : '0';
			$user_mail = ( $row['user_show_mail'] == 1 ) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail'] . '" /></a>' : '&nbsp;';
			
			$last_connect = !empty($row['last_connect']) ? $row['last_connect'] : $row['timestamp'];
		
			$this->view->assign_block_vars('member_list', array(
				'PSEUDO' => $row['login'],
				'MAIL' => $user_mail,
				'MSG' => $user_msg,
				'LAST_CONNECT' => gmdate_format('date_format_short', $last_connect),
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'U_USER_ID' => UserUrlBuilder::profile($row['user_id'])->absolute(),
				'U_USER_PM' => UserUrlBuilder::personnal_message($row['user_id'])->absolute()
			));
		}
	}

	private function build_response()
	{
		$response = new UserDisplayResponse();
		$response->set_page_title($this->lang['users']);
		$response->add_breadcrumb($this->lang['users'], UserUrlBuilder::users()->absolute());
		return $response->display($this->view);
	}
	
	public function get_right_controller_regarding_authorizations()
	{
		if (!AppContext::get_user()->check_auth(UserAccountsConfig::load()->get_auth_read_members(), AUTH_READ_MEMBERS))
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		return $this;
	}
}
?>