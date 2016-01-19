<?php
/*##################################################
 *                       AdminMemberAddController.class.php
 *                            -------------------
 *   begin                : December 27, 2010
 *   copyright            : (C) 2010 K�vin MASSY
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

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form($request);

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$display_name = $this->save();
			AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : AdminMembersUrlBuilder::management()), StringVars::replace_vars($this->lang['user.message.success.add'], array('name' => $display_name)));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminMembersDisplayResponse($tpl, LangLoader::get_message('members.add-member', 'admin-user-common'));
	}

	private function init()
	{
		$this->lang = LangLoader::get('user-common');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$security_config = SecurityConfig::load();
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('add_member', LangLoader::get_message('members.add-member', 'admin-user-common'));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('display_name', $this->lang['display_name'], '',
			array('maxlength' => 100, 'required' => true, 'events' => array('blur' => '
				if (!HTMLForms.getField("login").getValue() && HTMLForms.getField("display_name").validate() == "") {
					HTMLForms.getField("login").setValue(HTMLForms.getField("display_name").getValue().replace(/\s/g, \'\'));
				}')
			),
			array(new FormFieldConstraintLengthRange(3, 100), new FormFieldConstraintDisplayNameExists())
		));
		
		$fieldset->add_field($email = new FormFieldMailEditor('email', $this->lang['email'], '',
			array('required' => true),
			array(new FormFieldConstraintMailExist())
		));
		
		$fieldset->add_field(new FormFieldCheckbox('custom_login', $this->lang['login.custom'], false,
			array('events' => array('click' => '
				if (HTMLForms.getField("custom_login").getValue()) {
					HTMLForms.getField("login").enable();
				} else {
					HTMLForms.getField("login").disable();
				}')
			)
		));

		$fieldset->add_field($login = new FormFieldTextEditor('login', $this->lang['login'], '',
			array('required' => true, 'hidden' => true, 'maxlength' => 25),
			array(new FormFieldConstraintLengthRange(3, 25), new FormFieldConstraintPHPBoostAuthLoginExists())
		));

		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['password'], '',
			array('required' => true),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()), new FormFieldConstraintPasswordStrength())
		));
		
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['password.confirm'], '',
			array('required' => true),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()), new FormFieldConstraintPasswordStrength())
		));
		
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
		
		if ($security_config->are_login_and_email_forbidden_in_password())
		{
			$form->add_constraint(new FormConstraintFieldsInequality($email, $password));
			$form->add_constraint(new FormConstraintFieldsInequality($login, $password));
		}
		
		$fieldset->add_field(new FormFieldRanksSelect('rank', $this->lang['rank'], FormFieldRanksSelect::MEMBER));
		
		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}

	private function save()
	{
		$user = new User();
		$user->set_display_name($this->form->get_value('display_name'));
		$user->set_level($this->form->get_value('rank')->get_raw_value());
		$user->set_email($this->form->get_value('email'));
		
		$login = $this->form->get_value('email');
		if ($this->form->get_value('custom_login', false))
		{
			$login = $this->form->get_value('login');
		}
		
		$auth_method = new PHPBoostAuthenticationMethod($login, $this->form->get_value('password'));
		UserService::create($user, $auth_method);
		
		return $user->get_display_name();
	}
}
?>