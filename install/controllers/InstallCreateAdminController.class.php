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
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
            $installation_services = new InstallationServices(LangLoader::get_locale());
			$installation_services->create_admin(
			$this->form->get_value('login'), $this->form->get_value('password'),
			$this->form->get_value('email'), LangLoader::get_locale(),
			$this->form->get_value('createSession'), $this->form->get_value('autoconnect'));
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
		array('description' => $this->lang['admin.login.explanation'], 'required' => true));
		$login->add_constraint(new FormFieldConstraintLengthRange(3, 64));
		$fieldset->add_field($login);
        $password = new FormFieldPasswordEditor('password', $this->lang['admin.password'], '',
        array('description' => $this->lang['admin.password.explanation'], 'required' => true));
        $password->add_constraint(new FormFieldConstraintLengthRange(6, 64));
        $fieldset->add_field($password);
        $repeatPassword = new FormFieldPasswordEditor('repeatPassword', $this->lang['admin.password.repeat'], '',
        array('required' => true));
        $fieldset->add_field($repeatPassword);
        $this->form->add_constraint(new FormConstraintFieldsEquality($password, $repeatPassword));
        
		$email = new FormFieldTextEditor('email', $this->lang['admin.email'], '', array('required' => true));
		$email->add_constraint(new FormFieldConstraintMailAddress());
		$fieldset->add_field($email);
		$createSession = new FormFieldCheckbox('createSession', $this->lang['admin.connectAfterInstall'], true);
		$fieldset->add_field($createSession);
		$autoconnect = new FormFieldCheckbox('autoconnect', $this->lang['admin.autoconnect'], true);
		$fieldset->add_field($autoconnect);
		
		$this->submit_button = new FormButtonSubmitImg('templates/images/right.png', $this->lang['step.next'], 'submit');
		$this->form->add_button($this->submit_button);
	}
	
	/**
	 * @param Template $view
	 * @return InstallDisplayResponse
	 */
	private function create_response()
	{
		$this->view = new FileTemplate('install/admin.tpl');
		$this->view->add_subtemplate('ADMIN_FORM', $this->form->display());
		$step_title = $this->lang['step.admin.title'];
		$response = new InstallDisplayResponse(5, $step_title, $this->view);
		return $response;
	}
}
?>