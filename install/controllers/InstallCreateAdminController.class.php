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

	public function execute(HTTPRequestCustom $request)
	{
		parent::load_lang($request);
		$this->build_form();
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$login = $this->form->get_value('email');
			if ($this->form->get_value('custom_login', false))
			{
				$login = $this->form->get_value('login');
			}
			
			$installation_services = new InstallationServices();
			$installation_services->create_admin($this->form->get_value('display_name'), 
			$login, $this->form->get_value('password'),
			$this->form->get_value('email'), $this->form->get_value('createSession'),
			$this->form->get_value('autoconnect'));
			HtaccessFileCache::regenerate();
			AppContext::get_response()->redirect(InstallUrlBuilder::finish());
		}
		return $this->create_response();
	}

	private function build_form()
	{
		$security_config = SecurityConfig::load();
		$this->form = new HTMLForm('adminForm', '', false);
		
		$fieldset = new FormFieldsetHTML('adminAccount', $this->lang['admin.account']);
		$this->form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('display_name', LangLoader::get_message('display_name', 'user-common'), '',
			array('maxlength' => 100, 'required' => true, 'events' => array('blur' => '
				if (!HTMLForms.getField("login").getValue() && HTMLForms.getField("display_name").validate() == "") {
					HTMLForms.getField("login").setValue(HTMLForms.getField("display_name").getValue().replace(/\s/g, \'\'));
				}')
			),
			array(new FormFieldConstraintLengthRange(3, 100, $this->lang['admin.login.length']))
		));
		
		$fieldset->add_field(new FormFieldMailEditor('email', $this->lang['admin.email'], '', array('required' => true)));
		
		$fieldset->add_field(new FormFieldCheckbox('custom_login', LangLoader::get_message('login.custom', 'user-common'), false,
			array('description'=> LangLoader::get_message('login.custom.explain', 'user-common'), 'events' => array('click' => '
				if (HTMLForms.getField("custom_login").getValue()) {
					HTMLForms.getField("login").enable();
				} else {
					HTMLForms.getField("login").disable();
				}')
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('login', LangLoader::get_message('login', 'user-common'), '',
			array('required' => true, 'hidden' => true, 'maxlength' => 25),
			array(new FormFieldConstraintLengthRange(3, 25), new FormFieldConstraintPHPBoostAuthLoginExists())
		));
		
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['admin.password'], '',
			array('description' => StringVars::replace_vars($this->lang['admin.password.explanation'], array('number' => $security_config->get_internal_password_min_length())), 'required' => true),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length(), StringVars::replace_vars($this->lang['admin.password.length'], array('number' => $security_config->get_internal_password_min_length()))), new FormFieldConstraintPasswordStrength())
		));
		
		$fieldset->add_field($repeatPassword = new FormFieldPasswordEditor('repeatPassword', $this->lang['admin.password.repeat'], '',
			array('required' => true),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()), new FormFieldConstraintPasswordStrength())
		));
		
		$this->form->add_constraint(new FormConstraintFieldsEquality($password, $repeatPassword));

		$fieldset->add_field(new FormFieldCheckbox('createSession', $this->lang['admin.connectAfterInstall'], true));
		
		$fieldset->add_field(new FormFieldCheckbox('autoconnect', $this->lang['admin.autoconnect'], true));

		$action_fieldset = new FormFieldsetSubmit('actions');
		$back = new FormButtonLinkCssImg($this->lang['step.previous'], InstallUrlBuilder::website(), 'fas fa-arrow-left');
		$action_fieldset->add_element($back);
		$this->submit_button = new FormButtonSubmitCssImg($this->lang['step.next'], 'fas fa-arrow-right', 'admin');
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