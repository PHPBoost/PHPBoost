<?php
/*##################################################
 *                       AdminMemberEditController.class.php
 *                            -------------------
 *   begin                : February 28, 2010
 *   copyright            : (C) 2010 K�vin MASSY
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
	
	private $user;

	public function execute(HTTPRequest $request)
	{
		$user_id = $request->get_getint('id');
		$this->init();
		
		try {
			$this->user = UserService::get_user('WHERE user_id=:id', array('id' => $user_id));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}
		
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('proccess.success', 'errors-common'), E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminMembersDisplayResponse($tpl, $this->lang['members.edit-member']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-members-common');
	}

	private function build_form()
	{
		$form = new HTMLForm('member-edit');
		
		$fieldset = new FormFieldsetHTML('edit_member', $this->lang['members.edit-member']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('login', $this->lang['members.pseudo'], $this->user->get_pseudo(), array(
			'class' => 'text', 'maxlength' => 25, 'size' => 25, 'required' => true),
			array(new FormFieldConstraintLengthRange(3, 25), new FormFieldConstraintLoginExist($this->user->get_id()))
		));		
		
		$fieldset->add_field(new FormFieldTextEditor('mail', $this->lang['members.mail'], $this->user->get_email(), array(
			'class' => 'text', 'maxlength' => 255, 'description' => $this->lang['members.valid'], 'required' => true),
			array(new FormFieldConstraintMailAddress(), new FormFieldConstraintMailExist($this->user->get_id()))
		));
		
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['members.password'], ''));
		
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['members.confirm-password'], ''));
		
		$fieldset->add_field(new FormFieldCheckbox('user_hide_mail', $this->lang['members.hide-mail'], FormFieldCheckbox::CHECKED));

		$fieldset = new FormFieldsetHTML('member_management', $this->lang['members.member-management']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('approbation', $this->lang['members.approbation'], (bool)$this->user->get_approbation()));
		
		$fieldset->add_field(new FormFieldRanksSelect('rank', $this->lang['members.rank'], $this->user->get_level()));
		
		$fieldset->add_field(new FormFieldGroups('groups', $this->lang['members.groups'], $this->user->get_groups()));
		
		$fieldset_punishment = new FormFieldsetHTML('punishment_management', $this->lang['members.punishment-management']);
		$form->add_fieldset($fieldset_punishment);

		$fieldset_punishment->add_field(new FormFieldCheckbox('delete_account', LangLoader::get_message('del_member', 'main'), FormFieldCheckbox::UNCHECKED));
		
		$fieldset_punishment->add_field(new FormFieldMemberCaution('user_warning', $this->lang['members.caution'], $this->user->get_warning_percentage()));
		
		$fieldset_punishment->add_field(new FormFieldMemberSanction('user_readonly', $this->lang['members.readonly'], $this->user->get_is_readonly()));
		
		$fieldset_punishment->add_field(new FormFieldMemberSanction('user_ban', $this->lang['members.bannish'], $this->user->get_is_banned()));
		
		$member_extended_field = new MemberExtendedField();
		$member_extended_field->set_template($form);
		$member_extended_field->set_user_id($this->user->get_id());
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
		$user_id = $this->user->get_id();
		
		$this->user->set_pseudo($this->form->get_value('login'));
		$this->user->set_level($this->form->get_value('rank')->get_raw_value());
		$this->user->set_groups($this->form->get_value('groups'));
		$this->user->set_email($this->form->get_value('mail'));
		$this->user->set_show_email(!$this->form->get_value('user_hide_mail'));
		$this->user->set_approbation($this->form->get_value('approbation'));
		UserService::update($this->user, 'WHERE user_id=:id', array('id' => $user_id));

		if ($this->form->get_value('delete_account'))
		{
			UserService::delete_account('WHERE user_id=:user_id', array('user_id' => $user_id));
		}
		
		MemberExtendedFieldsService::register_fields($this->form, $user_id);
		
		if ($this->form->get_value('password') !== '')
		{
			UserService::change_password($this->form->get_value('password'), 'WHERE user_id=:user_id', array('user_id' => $user_id));
		}
		
		$user_warning = $this->form->get_value('user_warning')->get_raw_value();
		if (!empty($user_warning))
		{
			MemberSanctionManager::caution($user_id, $user_warning);
		}
		
		$user_readonly = $this->form->get_value('user_readonly')->get_raw_value();
		if (!empty($user_readonly))
		{
			MemberSanctionManager::remove_write_permissions($user_id, $user_readonly);
		}
		else
		{
			MemberSanctionManager::restore_write_permissions($user_id);
		}
		
		$user_ban = $this->form->get_value('user_readonly')->get_raw_value();
		if (!empty($user_ban))
		{
			MemberSanctionManager::banish($user_id, $user_ban);
		}
		else
		{
			MemberSanctionManager::cancel_banishment($user_id);
		}
		
		StatsCache::invalidate();
	}
}
?>