<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 27
 * @since       PHPBoost 3.0 - 2010 10 04
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

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
			NginxFileCache::regenerate();
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
			array(
				'class' => 'custom-checkbox',
				'description'=> LangLoader::get_message('login.custom.explain', 'user-common'),
				'events' => array('click' => '
					if (HTMLForms.getField("custom_login").getValue()) {
						HTMLForms.getField("login").enable();
					} else {
						HTMLForms.getField("login").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('login', LangLoader::get_message('login', 'user-common'), '',
			array('required' => true, 'hidden' => true, 'maxlength' => 25),
			array(new FormFieldConstraintLengthRange(3, 25), new FormFieldConstraintPHPBoostAuthLoginExists())
		));

		$fieldset->add_field(new FormFieldSpacer('1_separator', ''));

		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['admin.password'], '',
			array('description' => StringVars::replace_vars($this->lang['admin.password.explanation'], array('number' => $security_config->get_internal_password_min_length())), 'required' => true),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length(), StringVars::replace_vars($this->lang['admin.password.length'], array('number' => $security_config->get_internal_password_min_length()))), new FormFieldConstraintPasswordStrength())
		));

		$fieldset->add_field($repeatPassword = new FormFieldPasswordEditor('repeatPassword', $this->lang['admin.password.repeat'], '',
			array('required' => true),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()), new FormFieldConstraintPasswordStrength())
		));

		$this->form->add_constraint(new FormConstraintFieldsEquality($password, $repeatPassword));

		$fieldset->add_field(new FormFieldCheckbox('createSession', $this->lang['admin.connectAfterInstall'], true,
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('autoconnect', $this->lang['admin.autoconnect'], true,
			array('class' => 'custom-checkbox')
		));

		$action_fieldset = new FormFieldsetSubmit('actions', array('css_class' => 'fieldset-submit next-step'));
		$back = new FormButtonLinkCssImg($this->lang['step.previous'], InstallUrlBuilder::website(), 'fa fa-arrow-left');
		$action_fieldset->add_element($back);
		$this->submit_button = new FormButtonSubmitCssImg($this->lang['step.next'], 'fa fa-arrow-right', 'admin');
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
