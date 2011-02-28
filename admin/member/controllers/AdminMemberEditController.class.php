<?php
/*##################################################
 *                       AdminMemberEditController.class.php
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

class AdminMemberEditController extends AdminController
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
		$user_id = $request->get_getint('id');
		$this->init();
		
		if ($this->user_exist($user_id))
		{
			$this->build_form($user_id);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display($this->lang['members.success-member-edit'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return $this->build_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('members-common');
	}

	private function build_form($user_id)
	{
		$form = new HTMLForm('member-edit');
		
		$fieldset = new FormFieldsetHTML('edit_member', $this->lang['members.edit-member']);
		$form->add_fieldset($fieldset);
		
		$row = PersistenceContext::get_sql()->query_array(DB_TABLE_MEMBER, '*', "WHERE user_aprob = 1 AND user_id = '" . $user_id . "'", __LINE__, __FILE__);
		
		$fieldset->add_field(new FormFieldTextEditor('login', $this->lang['members.pseudo'], $row['login'], array(
			'class' => 'text', 'maxlength' => 25, 'size' => 25, 'required' => true)
		));		
		
		$fieldset->add_field(new FormFieldTextEditor('mail', $this->lang['members.mail'], $row['user_mail'], array(
			'class' => 'text', 'maxlength' => 255, 'description' => $this->lang['members.valid'], 'required' => true),
		array(new FormFieldConstraintMailAddress())
		));
		
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['members.password'], '', array(
			'class' => 'text', 'maxlength' => 25)
		));
		
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['members.confirm-password'], '', array(
			'class' => 'text', 'maxlength' => 25)
		));

		$fieldset->add_field(new FormFieldCheckbox('user_hide_mail', LangLoader::get_message('hide_mail', 'main'), FormFieldCheckbox::CHECKED));

		// TODO lang
		$fieldset = new FormFieldsetHTML('member_management', 'Management');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('approbation', 'Approbation', $row['user_aprob'],
			array(
				new FormFieldSelectChoiceOption($this->lang['members.yes'], '1'),
				new FormFieldSelectChoiceOption($this->lang['members.no'], '0'),
			)
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('rank', $this->lang['members.rank'], $row['level'],
			array(
				new FormFieldSelectChoiceOption($this->lang['members.rank.member'], '0'),
				new FormFieldSelectChoiceOption($this->lang['members.rank.modo'], '1'),
				new FormFieldSelectChoiceOption($this->lang['members.rank.admin'], '2')
			)
		));
		
		$fieldset->add_field(new FormFieldCheckbox('delete_account', LangLoader::get_message('del_member', 'main'), FormFieldCheckbox::UNCHECKED));
		
		$member_extended_field = new MemberExtendedField();
		$member_extended_field->set_template($form);
		$member_extended_field->set_user_id($user_id);
		$member_extended_field->set_is_admin(true);
		MemberExtendedFieldsService::display_form_fields($member_extended_field);
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function save()
	{

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
		$response->add_link($this->lang['members.members-management'], DispatchManager::get_url('/admin/member/index.php', '/member/list'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$response->add_link($this->lang['members.add-member'], DispatchManager::get_url('/admin/member/index.php', '/member/add'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$response->add_link($this->lang['members.config-members'], DispatchManager::get_url('/admin/member/index.php', '/member/config'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$response->add_link($this->lang['members.members-punishment'], DispatchManager::get_url('/admin/member/index.php', '/member/punishment'), '/templates/' . get_utheme() . '/images/admin/members.png');
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['members.edit-member']);
		
		return $response;
	}
	
	private function user_exist($user_id)
	{
		return PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE user_aprob = 1 AND user_id = '" . $user_id . "'") > 0 ? true : false;
	}
}

?>