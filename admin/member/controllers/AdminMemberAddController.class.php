<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 19
 * @since       PHPBoost 3.0 - 2010 12 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminMemberAddController extends DefaultAdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form($request);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$display_name = $this->save();
			AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : AdminMembersUrlBuilder::management()), StringVars::replace_vars($this->lang['user.message.success.add'], array('name' => $display_name)));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminMembersDisplayResponse($this->view, $this->lang['user.add.member']);
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$security_config = SecurityConfig::load();
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('add_member', $this->lang['user.add.member']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field($display_name = new FormFieldTextEditor('display_name', $this->lang['user.display.name'], '',
			array(
				'maxlength' => 100, 'required' => true,
				'events' => array('blur' => '
					if (!HTMLForms.getField("login").getValue() && HTMLForms.getField("display_name").validate() == "") {
						HTMLForms.getField("login").setValue(HTMLForms.getField("display_name").getValue().replace(/\s/g, \'\'));
					}'
				)
			),
			array(new FormFieldConstraintLengthRange(3, 100), new FormFieldConstraintDisplayNameExists())
		));

		$fieldset->add_field($email = new FormFieldMailEditor('email', $this->lang['user.email'], '',
			array('required' => true),
			array(new FormFieldConstraintMailExist())
		));

		$fieldset->add_field(new FormFieldRanksSelect('rank', $this->lang['user.rank'], FormFieldRanksSelect::MEMBER));

		$fieldset->add_field(new FormFieldSpacer('define_identifier', ''));

		$fieldset->add_field($custom_login_checked = new FormFieldCheckbox('custom_login', $this->lang['user.username.custom'], false,
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("custom_login").getValue()) {
						HTMLForms.getField("login").enable();
					} else {
						HTMLForms.getField("login").disable();
					}'
				)
			)
		));

		$fieldset->add_field($login = new FormFieldTextEditor('login', $this->lang['user.username'], '',
			array('required' => true, 'hidden' => true, 'maxlength' => 25),
			array(new FormFieldConstraintLengthRange(3, 25), new FormFieldConstraintPHPBoostAuthLoginExists())
		));

		$fieldset->add_field(new FormFieldSpacer('define_password', ''));

		$fieldset->add_field(new FormFieldCheckbox('custom_password', $this->lang['user.password.custom'], false,
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['user.password.custom.clue'],
				'events' => array('click' => '
					if (HTMLForms.getField("custom_password").getValue()) {
						HTMLForms.getField("password").enable();
						HTMLForms.getField("password_bis").enable();
					} else {
						HTMLForms.getField("password").disable();
						HTMLForms.getField("password_bis").disable();
					}'
				)
			)
		));

		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['user.password'], '',
			array('required' => true, 'autocomplete' => false, 'hidden' => true),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()), new FormFieldConstraintPasswordStrength())
		));

		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['user.password.confirm'], '',
			array('required' => true, 'autocomplete' => false, 'hidden' => true),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()), new FormFieldConstraintPasswordStrength())
		));

		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));

		if ($security_config->are_login_and_email_forbidden_in_password())
		{
			$form->add_constraint(new FormConstraintFieldsNotIncluded($email, $password));
			$form->add_constraint(new FormConstraintFieldsNotIncluded($custom_login_checked == 'on' ? $login : $display_name, $password));
		}

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
		if ($this->form->get_value('custom_login'))
		{
			$login = $this->form->get_value('login');
		}

		if ($this->form->get_value('custom_password'))
		{
			$password = $this->form->get_value('password');
		}
		else
		{
			$password = KeyGenerator::generate_key(8);
		}

		$auth_method = new PHPBoostAuthenticationMethod($login, $password);
		$user_id = UserService::create($user, $auth_method);

		if ($user_id)
		{
			$user->set_id($user_id);
			$registration_pass = UserAccountsConfig::load()->get_member_accounts_validation_method() == UserAccountsConfig::MAIL_USER_ACCOUNTS_VALIDATION ? KeyGenerator::generate_key(15) : '';
			UserRegistrationService::send_email_confirmation($user_id, $user->get_email(), $user->get_display_name(), $login, $password, $registration_pass, true);
			
			HooksService::execute_hook_action('add_user', 'user', array_merge($user->get_properties(), array('title' => $user->get_display_name(), 'url' => UserUrlBuilder::profile($user_id)->rel())));
		}
		
		return $user->get_display_name();
	}
}
?>
