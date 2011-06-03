<?php
/*##################################################
 *                      AdminViewAllMembersController.class.php
 *                            -------------------
 *   begin                : February 28, 2010
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

class AdminViewAllMembersController extends AdminController
{
	private $lang;
	
	private $view;
	
	private $nbr_members_per_page = 25;

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_view($request);

		return $this->build_response($this->view);
	}

	private function build_form()
	{
		$form = new HTMLForm('search_member');
		
		$fieldset = new FormFieldsetHTML('search_member', $this->lang['members.member-search']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldAjaxCompleter('search_member', $this->lang['members.pseudo'], '', array(
			'file' => 'test', 'name_parameter' => 'pseudo'))
		);

		return $form;
	}
	
	private function build_view($request)
	{
		$field = $request->get_value('field', 'timestamp');
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
			case 'level' :
				$field_bdd = 'level';
			break;
			case 'login' :
				$field_bdd = 'login';
			break;
			case 'approbation' :
				$field_bdd = 'user_aprob';
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
			'SORT_LOGIN_BOTTOM' => DispatchManager::get_url('/admin/member', '/member/login/bottom/'. $page)->absolute(),
			'SORT_REGISTERED_TOP' => DispatchManager::get_url('/admin/member', '/member/registered/top/'. $page)->absolute(),
			'SORT_REGISTERED_BOTTOM' => DispatchManager::get_url('/admin/member', '/member/registered/bottom/'. $page)->absolute(),
			'SORT_LEVEL_TOP' => DispatchManager::get_url('/admin/member', '/member/level/top/'. $page)->absolute(),
			'SORT_LEVEL_BOTTOM' => DispatchManager::get_url('/admin/member', '/member/level/bottom/'. $page)->absolute(),
			'SORT_APPROBATION_TOP' => DispatchManager::get_url('/admin/member', '/member/approbation/top/'. $page)->absolute(),
			'SORT_APPROBATION_BOTTOM' => DispatchManager::get_url('/admin/member', '/member/approbation/bottom/'. $page)->absolute(),
			'SORT_LAST_CONNECT_TOP' => DispatchManager::get_url('/admin/member', '/member/connect/top/'. $page)->absolute(),
			'SORT_LAST_CONNECT_BOTTOM' => DispatchManager::get_url('/admin/member', '/member/connect/bottom'. $page)->absolute(),
			'PAGINATION' => '&nbsp;<strong>' . $this->lang['page'] . ' :</strong> ' . $pagination->export()->render(),
			'FORM' => $this->build_form()->display()
		));

		$limite_page = $page > 0 ? $page : 1;
		$limite_page = (($limite_page - 1) * $this->nbr_members_per_page);
		
		$result = PersistenceContext::get_querier()->select("SELECT user_id, login, user_mail, timestamp, last_connect, level, user_aprob
		FROM " . DB_TABLE_MEMBER . "
		ORDER BY ". $field_bdd ." ". $mode ."
		LIMIT ". $this->nbr_members_per_page ." OFFSET :start_limit",
			array(
				'start_limit' => $limite_page
			), SelectQueryResult::FETCH_ASSOC
		);
		while ($row = $result->fetch())
		{
			$user_mail = '<a href="mailto:' . $row['user_mail'] . '"><img src="'. PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail'] . '" /></a>';
			$row['last_connect'] = !empty($row['last_connect']) ? $row['last_connect'] : $row['timestamp'];
		
			$this->view->assign_block_vars('member_list', array(
				'DELETE_LINK' => DispatchManager::get_url('/admin/member', '/member/'. $row['user_id'] .'/delete/')->absolute(),
				'EDIT_LINK' => DispatchManager::get_url('/admin/member', '/member/'. $row['user_id'] .'/edit/')->absolute(),
				'PSEUDO' => $row['login'],
				'LEVEL' => $this->get_lang_level($row['level']),
				'APPROBATION' => $row['user_aprob'] == 0 ? $this->lang['no'] : $this->lang['yes'],
				'MAIL' => $user_mail,
				'LAST_CONNECT' => gmdate_format('date_format_short', $row['last_connect']),
				'REGISTERED' => gmdate_format('date_format_short', $row['timestamp']),
				'U_USER_ID' => DispatchManager::get_url('/member', '/profile/'. $row['user_id'] . '/')->absolute()
			));
		}
	}
	
	private function get_lang_level($level)
	{
		if ($level == '2')
		{
			return $this->lang['admin'];
		}
		elseif ($level == '1')
		{
			return $this->lang['modo'];
		}
		else 
		{
			return $this->lang['member'];
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('main');
		$this->view = new FileTemplate('admin/member/AdminViewAllMembersController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_response(View $view)
	{
		$response = new AdminMenuDisplayResponse($view);
		$response->set_title($this->lang['members.members-management']);
		$response->add_link($this->lang['members.members-management'], DispatchManager::get_url('/admin/member', '/member/'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$response->add_link($this->lang['members.add-member'], DispatchManager::get_url('/admin/member', '/member/add'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$response->add_link($this->lang['members.config-members'], DispatchManager::get_url('/admin/member', '/member/config'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$response->add_link($this->lang['members.members-punishment'], DispatchManager::get_url('/admin/member', '/member/punishment'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['members.members-management']);
		
		return $response;
	}
}

?>