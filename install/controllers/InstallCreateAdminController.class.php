<?php
/*##################################################
 *                         InstallCreateAdminController.class.php
 *                            -------------------
 *   begin                : October 04 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class InstallCreateAdminController extends InstallController
{
	/**
	 * @var Template
	 */
	private $view;

	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var HTMLForm
	 */
	private $submit_button;

	public function execute(HTTPRequest $request)
	{
		parent::load_lang($request);
		$this->build_form();
		if ($this->submit_button->has_been_submitted() && $this->form->validate())
		{
            $installation_services = new InstallationServices();
			$installation_services->create_admin(
			$this->form->get_value('login'), $this->form->get_value('password'),
			$this->form->get_value('email'), $this->form->get_value('createSession'),
			$this->form->get_value('autoconnect'));
			AppContext::get_response()->redirect(InstallUrlBuilder::finish());
		}
		return $this->create_response();
	}

	private function build_form()
	{
		$this->form = new HTMLForm('adminForm');

		$fieldset = new FormFieldsetHTML('adminAccount', $this->lang['admin.account']);
		$this->form->add_fieldset($fieldset);

		$login = new FormFieldTextEditor('login', $this->lang['admin.login'], '',
		array('description' => $this->lang['admin.login.explanation'], 'required' => $this->lang['admin.login.required'], 'maxlength' => 64));
		$login->add_constraint(new FormFieldConstraintLengthRange(3, 64, $this->lang['admin.login.length']));
		$fieldset->add_field($login);
        $password = new FormFieldPasswordEditor('password', $this->lang['admin.password'], '',
        array('description' => $this->lang['admin.password.explanation'], 'required' => $this->lang['admin.password.required'], 'maxlength' => 64));
        $password->add_constraint(new FormFieldConstraintLengthRange(6, 64, $this->lang['admin.password.length']));
        $fieldset->add_field($password);
        $repeatPassword = new FormFieldPasswordEditor('repeatPassword', $this->lang['admin.password.repeat'], '',
        array('required' => $this->lang['admin.confirmPassword.required']));
        $fieldset->add_field($repeatPassword);
        $this->form->add_constraint(new FormConstraintFieldsEquality($password, $repeatPassword, $this->lang['admin.passwords.mismatch']));

		$email = new FormFieldTextEditor('email', $this->lang['admin.email'], '', array('required' => $this->lang['admin.email.required']));
		$email->add_constraint(new FormFieldConstraintMailAddress($this->lang['admin.email.invalid']));
		$fieldset->add_field($email);
		$createSession = new FormFieldCheckbox('createSession', $this->lang['admin.connectAfterInstall'], true);
		$fieldset->add_field($createSession);
		$autoconnect = new FormFieldCheckbox('autoconnect', $this->lang['admin.autoconnect'], true);
		$fieldset->add_field($autoconnect);

		$action_fieldset = new FormFieldsetSubmit('actions');
		$back = new FormButtonLink($this->lang['step.previous'], InstallUrlBuilder::website(), 'templates/images/left.png');
		$action_fieldset->add_element($back);
		$this->submit_button = new FormButtonSubmitImg($this->lang['step.next'], 'templates/images/right.png', 'admin');
		$action_fieldset->add_element($this->submit_button);
		$this->form->add_fieldset($action_fieldset);
	}

	/**
	 * @return InstallDisplayResponse
	 */
	private function create_response()
	{
		$this->view = new FileTemplate('install/admin.tpl');
		$this->view->put('ADMIN_FORM', $this->form->display());
		$step_title = $this->lang['step.admin.title'];
		$response = new InstallDisplayResponse(5, $step_title, $this->view);
		return $response;
	}
}
?>