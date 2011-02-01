<?php
/*##################################################
 *                       AdminMemberAddController.class.php
 *                            -------------------
 *   begin                : December 27, 2010
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

class AdminMemberAddController extends AdminController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			$this->form->put('MSG', MessageHelper::display($this->lang['members.success-member-add'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return $this->build_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('members-common');
	}

	private function build_form()
	{
		$form = new HTMLForm('member-add');
		
		$fieldset = new FormFieldsetHTML('add_member', $this->lang['members.add-member']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('login', $this->lang['members.pseudo'], '', array(
			'class' => 'text', 'maxlength' => 25, 'size' => 25, 'required' => true)
		));		
		
		$fieldset->add_field(new FormFieldTextEditor('mail', $this->lang['members.mail'], '', array(
			'class' => 'text', 'maxlength' => 255, 'description' => $this->lang['members.valid'], 'required' => true),
		array(new FormFieldConstraintMailAddress())
		));
		
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['members.password'], '', array(
			'class' => 'text', 'maxlength' => 25, 'required' => true)
		));
		
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['members.confirm-password'], '', array(
			'class' => 'text', 'maxlength' => 25, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('rank', $this->lang['members.rank'], '1',
			array(
				new FormFieldSelectChoiceOption($this->lang['members.rank.member'], '1'),
				new FormFieldSelectChoiceOption($this->lang['members.rank.modo'], '2'),
				new FormFieldSelectChoiceOption($this->lang['members.rank.admin'], '3')
			)
		));
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function save()
	{
		$this->register_member();
	}
	
	private function register_member()
	{
		PersistenceContext::get_querier()->inject(
			"INSERT INTO " . DB_TABLE_MEMBER . " (login,password,level,user_groups,user_mail,user_show_mail,timestamp,user_pm,user_warning,last_connect,test_connect, new_pass,user_ban,user_aprob)
			VALUES (:login, :password, :level, '', :user_mail, 0, :timestamp, 0, 0, :last_connect, 0, '', 0, :user_aprob)", array(
				'login' => $this->form->get_value('login'),
				'password' => strhash($this->form->get_value('password')),
				'level' => $this->get_rank_member(),
				'user_mail' => $this->form->get_value('mail'),
				'timestamp' => time(),
				'last_connect' => '',
				'user_aprob' => '1'
		));
	}
	
	private function get_rank_member()
	{
		$rank = $this->form->get_value('rank')->get_raw_value();
		if ($rank == '3')
		{
			return '2';
		}
		elseif ($rank == '2')
		{
			return '1';
		}
		else
		{
			return '0';
		}
	}

	private function build_response(View $view)
	{
		$response = new AdminMenuDisplayResponse($view);
		$response->set_title($this->lang['members.members-management']);
		$response->add_link($this->lang['members.members-management'], DispatchManager::get_url('/admin/member/index.php', '/user/list'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$response->add_link($this->lang['members.add-member'], DispatchManager::get_url('/admin/member/index.php', '/user/add'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$response->add_link($this->lang['members.config-members'], DispatchManager::get_url('/admin/member/index.php', '/user/config'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$response->add_link($this->lang['members.members-punishment'], DispatchManager::get_url('/admin/member/index.php', '/user/punishment'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['members.add-member']);
		
		return $response;
	}
}

?>