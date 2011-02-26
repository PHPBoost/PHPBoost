<?php
/*##################################################
 *                       MemberEditProfileController.class.php
 *                            -------------------
 *   begin                : September 18, 2010 2009
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

class MemberEditProfileController extends AbstractController
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

		$user_id = AppContext::get_user()->get_id();

		if ($this->user_exist($user_id))
		{
			$this->build_form($user_id);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}

		$tpl = new StringTemplate('# INCLUDE FORM #');

		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submitted() && $this->form->validate())
		{
			$this->save($user_id);
		}

		$tpl->put('FORM', $this->form->display());

		return $this->build_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('main');
	}

	private function build_form($user_id)
	{
		$form = new HTMLForm('member_edit_profile');

		$fieldset = new FormFieldsetHTML('edit_profile', $this->lang['profile_edition']);
		$form->add_fieldset($fieldset);

		$row = PersistenceContext::get_sql()->query_array(DB_TABLE_MEMBER, '*', "WHERE user_aprob = 1 AND user_id = '" . $user_id . "' " , __LINE__, __FILE__);

		$fieldset->add_field(new FormFieldTextEditor('mail', $this->lang['mail'], $row['user_mail'], array(
			'class' => 'text', 'maxlength' => 255, 'description' => $this->lang['valid']),
		array(new FormFieldConstraintMailAddress())
		));

		$fieldset->add_field(new FormFieldPasswordEditor('old_password', $this->lang['previous_password'], '', array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['fill_only_if_modified']))
		);

		$fieldset->add_field($new_password = new FormFieldPasswordEditor('new_password', $this->lang['password'], '', array(
			'class' => 'text', 'maxlength' => 25))
		);
		$fieldset->add_field($new_password_bis = new FormFieldPasswordEditor('new_password_bis', $this->lang['confirm_password'], '', array(
			'class' => 'text', 'maxlength' => 25))
		);

		$fieldset->add_field(new FormFieldCheckbox('user_hide_mail', $this->lang['hide_mail'], $row['user_show_mail']));

		$fieldset->add_field(new FormFieldCheckbox('delete_account', $this->lang['del_member'], FormFieldCheckbox::UNCHECKED));

		$member_extended_field = new MemberExtendedField();
		$member_extended_field->set_template($form);
		$member_extended_field->set_user_id($user_id);
		MemberExtendedFieldsService::display_form_fields($member_extended_field);

		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_constraint(new FormConstraintFieldsEquality($new_password, $new_password_bis));
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function save($user_id)
	{
		$mail = $this->form->get_value('mail');
		MemberUpdateProfileHelper::update_profile($this->form, $user_id, $mail);
		$this->change_password($user_id);
		$delete_account = $this->form->get_value('delete_account');
		if ((bool)$delete_account)
		{
			MemberUpdateProfileHelper::delete_account($user_id);
		}
	}

	private function change_password($user_id)
	{
		$old_password = strhash($this->form->get_value('old_password'));
		$new_password = $this->form->get_value('new_password');
		$old_password_bdd = MemberUpdateProfileHelper::get_old_password($user_id);

		if (!empty($new_password) && $old_password == $old_password_bdd)
		{
			MemberUpdateProfileHelper::change_password(strhash($new_password), $user_id);
		}
	}

	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['profile_edition']);
		return $response;
	}

	private function user_exist($user_id)
	{
		return PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE user_aprob = 1 AND user_id = '" . $user_id . "'") > 0 ? true : false;
	}
}

?>