<?php
/*##################################################
 *                      AdminViewAllMembersController.class.php
 *                            -------------------
 *   begin                : February 28, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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

class AdminViewAllMembersController extends AdminController
{
	private $lang;
	
	private $view;
	
	private $nbr_members_per_page = 25;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view($request);
		
		return new AdminMembersDisplayResponse($this->view, LangLoader::get_message('members.members-management', 'admin-members-common'));
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('search_member', $this->lang['search_member']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldAjaxSearchUserAutoComplete('member', $this->lang['pseudo'], '', array(
			'maxlength' => 25, 'size' => 25))
		);
		
		return $form;
	}
	
	private function build_view($request)
	{
		$admin_lang = LangLoader::get('admin');
		$user_common_lang = LangLoader::get('user-common');
		
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
		
		$nbr_member = PersistenceContext::get_querier()->count(DB_TABLE_MEMBER);
		$nb_pages = ceil($nbr_member / $this->nbr_members_per_page);
		$pagination = new Pagination($nb_pages, $page);
		$pagination->set_url_sprintf_pattern(AdminMembersUrlBuilder::management($field . '/' . $sort . '/%d')->absolute());
		$this->view->put_all(array(
			'C_PAGINATION' => $nb_pages > 1,
			'SORT_LOGIN_TOP' => AdminMembersUrlBuilder::management('login/top/'. $page)->absolute(),
			'SORT_LOGIN_BOTTOM' => AdminMembersUrlBuilder::management('login/bottom/'. $page)->absolute(),
			'SORT_LEVEL_TOP' => AdminMembersUrlBuilder::management('level/top/'. $page)->absolute(),
			'SORT_LEVEL_BOTTOM' => AdminMembersUrlBuilder::management('level/bottom/'. $page)->absolute(),
			'SORT_REGISTERED_TOP' => AdminMembersUrlBuilder::management('registered/top/'. $page)->absolute(),
			'SORT_REGISTERED_BOTTOM' => AdminMembersUrlBuilder::management('registered/bottom/'. $page)->absolute(),
			'SORT_LAST_CONNECT_TOP' => AdminMembersUrlBuilder::management('connect/top/'. $page)->absolute(),
			'SORT_LAST_CONNECT_BOTTOM' => AdminMembersUrlBuilder::management('connect/bottom'. $page)->absolute(),
			'SORT_APPROBATION_TOP' => AdminMembersUrlBuilder::management('approbation/top/'. $page)->absolute(),
			'SORT_APPROBATION_BOTTOM' => AdminMembersUrlBuilder::management('approbation/bottom/'. $page)->absolute(),
			'L_CONFIRM_DEL_USER' => $admin_lang['confirm_del_member'],
			'L_CONFIRM_DEL_ADMIN' => $admin_lang['confirm_del_admin'],
			'L_USERS_MANAGEMENT' => $admin_lang['members_management'],
			'L_LOGIN' => $user_common_lang['pseudo'],
			'L_MAIL' => $user_common_lang['email'],
			'L_LEVEL' => $user_common_lang['level'],
			'L_APPROBATION' => $user_common_lang['approbation'],
			'L_LAST_CONNECT' => $this->lang['last_connect'],
			'L_REGISTERED' => $admin_lang['registered'],
			'L_UPDATE' => $this->lang['update'],
			'L_DELETE' => $this->lang['delete'],
			'PAGINATION' => $pagination->export()->render(),
			'FORM' => $this->build_form()->display()
		));
		
		$limit_page = $page > 0 ? $page : 1;
		$limit_page = (($limit_page - 1) * $this->nbr_members_per_page);
		
		$result = PersistenceContext::get_querier()->select("SELECT user_id, login, user_mail, timestamp, last_connect, level, user_groups, user_aprob
		FROM " . DB_TABLE_MEMBER . "
		ORDER BY ". $field_bdd ." ". $mode ."
		LIMIT ". $this->nbr_members_per_page ." OFFSET :start_limit",
			array(
				'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		while ($row = $result->fetch())
		{
			$group_color = User::get_group_color($row['user_groups'], $row['level']);
			
			$this->view->assign_block_vars('member_list', array(
				'C_GROUP_COLOR' => !empty($group_color),
				'DELETE_LINK' => AdminMembersUrlBuilder::delete($row['user_id'])->absolute(),
				'EDIT_LINK' => AdminMembersUrlBuilder::edit($row['user_id'])->absolute(),
				'LOGIN' => $row['login'],
				'LEVEL' => UserService::get_level_lang($row['level']),
				'LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'GROUP_COLOR' => $group_color,
				'APPROBATION' => $row['user_aprob'] == 0 ? $this->lang['no'] : $this->lang['yes'],
				'MAIL' => $row['user_mail'],
				'LAST_CONNECT' => !empty($row['last_connect']) ? gmdate_format('date_format_short', $row['last_connect']) : $this->lang['never'],
				'REGISTERED' => gmdate_format('date_format_short', $row['timestamp']),
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->absolute()
			));
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('main');
		$this->view = new FileTemplate('admin/member/AdminViewAllMembersController.tpl');
		$this->view->add_lang($this->lang);
	}
}
?>
