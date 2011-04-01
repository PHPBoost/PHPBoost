<?php
/*##################################################
 *                      MemberViewAllMembersController.class.php
 *                            -------------------
 *   begin                : February 9, 2010 2009
 *   copyright            : (C) 2010 Kévin MASSY
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

class MemberViewAllMembersController extends AbstractController
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

	private function build_form($request)
	{
		$field = $request->get_value('field', 'login');
		$sort = $request->get_value('sort', 'top');
		$page = $request->get_int('page', 1);
		
		if (!AppContext::get_user()->check_auth(UserAccountsConfig::load()->get_auth_read_members(), AUTH_READ_MEMBERS))
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
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
		
		$nbr_member = PersistenceContext::get_sql()->count_table(DB_TABLE_MEMBER, __LINE__, __FILE__);
		$nb_pages =  ceil($nbr_member / $this->nbr_members_per_page);
		$pagination = new Pagination($nb_pages, $page);
		$pagination->set_url_sprintf_pattern(DispatchManager::get_url('/member', '/member/'. $field .'/'. $sort .'/%d')->absolute());
		$this->view->put_all(array(
			'SORT_LOGIN_TOP' => DispatchManager::get_url('/member', '/member/login/top/'. $page)->absolute(),
			'SORT_LOGIN_BOTTOM' => DispatchManager::get_url('/member', '/member/login/bottom/'. $page)->absolute(),
			'SORT_REGISTERED_TOP' => DispatchManager::get_url('/member', '/member/registered/top/'. $page)->absolute(),
			'SORT_REGISTERED_BOTTOM' => DispatchManager::get_url('/member', '/member/registered/bottom/'. $page)->absolute(),
			'SORT_MSG_TOP' => DispatchManager::get_url('/member', '/member/messages/top/'. $page)->absolute(),
			'SORT_MSG_BOTTOM' => DispatchManager::get_url('/member', '/member/messages/bottom/'. $page)->absolute(),
			'SORT_LAST_CONNECT_TOP' => DispatchManager::get_url('/member', '/member/connect/top/'. $page)->absolute(),
			'SORT_LAST_CONNECT_BOTTOM' => DispatchManager::get_url('/member', '/member/connect/bottom'. $page)->absolute(),
			'PAGINATION' => '&nbsp;<strong>' . $this->lang['page'] . ' :</strong> ' . $pagination->export()->render()
		));

		$limite_page = $page > 0 ? $page : 1;
		$limite_page = (($limite_page - 1) * $this->nbr_members_per_page);
		
		$result = PersistenceContext::get_querier()->select("SELECT user_id, login, user_mail, user_show_mail, timestamp, user_msg, last_connect
		FROM " . DB_TABLE_MEMBER . "
		WHERE user_aprob = 1
		ORDER BY ". $field_bdd ." ". $mode ."
		LIMIT ". $this->nbr_members_per_page ." OFFSET :start_limit",
			array(
				'start_limit' => $limite_page
			), SelectQueryResult::FETCH_ASSOC
		);
		while ($row = $result->fetch())
		{
			$user_msg = !empty($row['user_msg']) ? $row['user_msg'] : '0';
			$user_mail = ( $row['user_show_mail'] == 1 ) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail'] . '" /></a>' : '&nbsp;';
			
			$row['last_connect'] = !empty($row['last_connect']) ? $row['last_connect'] : $row['timestamp'];
		
			$this->view->assign_block_vars('member_list', array(
				'PSEUDO' => $row['login'],
				'MAIL' => $user_mail,
				'MSG' => $user_msg,
				'LAST_CONNECT' => gmdate_format('date_format_short', $row['last_connect']),
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'U_USER_ID' => DispatchManager::get_url('/member', '/profile/'. $row['user_id'] . '/')->absolute(),
				'U_USER_PM' => url('pm.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php')
			));
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('main');
		$this->view = new FileTemplate('member/MemberViewAllMembersController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['member_area']);
		return $response;
	}
}

?>