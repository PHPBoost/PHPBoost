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
	
	private $user_id;

	public function execute(HTTPRequest $request)
	{
		$this->user_id = $request->get_getint('id');
		$this->init();
		
		if ($this->user_exist())
		{
			$this->build_form();
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
			$tpl->put('MSG', MessageHelper::display($this->lang['members.member-edit.success'], E_USER_SUCCESS, 4));
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
		
		$row = PersistenceContext::get_sql()->query_array(DB_TABLE_MEMBER, '*', "WHERE user_id = '" . $this->user_id . "'", __LINE__, __FILE__);
		
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
		
		$fieldset->add_field(new FormFieldCheckbox('user_hide_mail', $this->lang['members.hide-mail'], FormFieldCheckbox::CHECKED));

		$fieldset = new FormFieldsetHTML('member_management', $this->lang['members.member-management']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('approbation', $this->lang['members.approbation'], (bool)$row['user_aprob']));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('rank', $this->lang['members.rank'], $row['level'],
			array(
				new FormFieldSelectChoiceOption($this->lang['members.rank.member'], '0'),
				new FormFieldSelectChoiceOption($this->lang['members.rank.modo'], '1'),
				new FormFieldSelectChoiceOption($this->lang['members.rank.admin'], '2')
			)
		));
		
		$fieldset->add_field(new FormFieldMultipleSelectChoice('groups', $this->lang['members.groups'], explode('|', $row['user_groups']), $this->get_groups()));
		
		$fieldset = new FormFieldsetHTML('punishment_management', $this->lang['members.punishment-management']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('delete_account', LangLoader::get_message('del_member', 'main'), FormFieldCheckbox::UNCHECKED));
		
		$member_extended_field = new MemberExtendedField();
		$member_extended_field->set_template($form);
		$member_extended_field->set_user_id($this->user_id);
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
		$condition = "WHERE user_id = :user_id";
		$columns = array(
			'login' => $this->form->get_value('login'),
			'level' => $this->get_rank_member(),
			'user_groups' => implode('|', $this->form->get_value('groups')),
			'user_mail' => $this->form->get_value('mail'),
			'user_show_mail' => (string)!$this->form->get_value('user_hide_mail'),
			'user_aprob' => (string)$this->form->get_value('approbation')
		);
		$parameters = array('user_id' => $this->user_id);
		PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, $columns, $condition, $parameters);
		
		if ($this->form->get_value('delete_account'))
		{
			PersistenceContext::get_querier()->delete(DB_TABLE_MEMBER, "WHERE user_id = :user_id", array('user_id' => $this->user_id));
		}
		
		MemberExtendedFieldsService::register_fields($this->form, $this->user_id);
		
		if ($this->form->get_value('password') !== '')
		{
			$condition = "WHERE user_id = :user_id";
			$columns = array('password' => $this->form->get_value('password'));
			$parameters = array('user_id' => $this->user_id);
			PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, $columns, $condition, $parameters);
		}
		
		StatsCache::invalidate();
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
	
	public function get_groups()
	{
		$groups = array();
		foreach (GroupsCache::load()->get_groups() as $id => $values)
		{
			$groups[] = new FormFieldSelectChoiceOption($value['name'], $id);
		}
		return $groups;
	}
	
	private function user_exist()
	{
		return PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE user_id = '" . $this->user_id . "'") > 0 ? true : false;
	}
}

?>