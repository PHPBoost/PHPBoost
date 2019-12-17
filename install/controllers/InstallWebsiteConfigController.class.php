<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 01 29
 * @since       PHPBoost 3.0 - 2010 10 03
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class InstallWebsiteConfigController extends InstallController
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

	private $security_config;
	private $server_configuration;

	/**
	 * @var mixed[string] Distribution configuration
	 */
	private $distribution_config;

	public function execute(HTTPRequestCustom $request)
	{
		parent::load_lang($request);
		$this->init();
		$this->build_form();
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->handle_form();
		}
		return $this->create_response();
	}

	private function init()
	{
		$this->security_config = SecurityConfig::load();
		$this->server_configuration = new ServerConfiguration();
		$this->distribution_config = parse_ini_file(PATH_TO_ROOT . '/install/distribution.ini');
	}

	private function build_form()
	{
		$admin_user_lang = LangLoader::get('admin-user-common');
		$this->form = new HTMLForm('websiteForm', '', false);

		$fieldset = new FormFieldsetHTML('yourSite', $this->lang['website.yours']);
		$this->form->add_fieldset($fieldset);

		$host = new FormFieldUrlEditor('host', $this->lang['website.host'], $this->current_server_host(),
		array('description' => $this->lang['website.host.explanation'], 'required' => $this->lang['website.host.required']));
		$host->add_event('change', $this->warning_if_not_equals($host, $this->lang['website.host.warning']));
		$fieldset->add_field($host);

		$path = new FormFieldTextEditor('path', $this->lang['website.path'], $this->current_server_path(),
		array('description' => $this->lang['website.path.explanation']));
		$path->add_event('change', $this->warning_if_not_equals($path, $this->lang['website.path.warning']));
		$fieldset->add_field($path);

		$fieldset->add_field(new FormFieldTextEditor('name', $this->lang['website.name'], '', array('required' => $this->lang['website.name.required'])));

		$fieldset->add_field(new FormFieldTextEditor('slogan', $this->lang['website.slogan'], ''));

		$fieldset->add_field(new FormFieldMultiLineTextEditor('description', $this->lang['website.description'], '',
			array('description' => $this->lang['website.description.explanation'])
		));

		$fieldset->add_field(new FormFieldTimezone('timezone', $this->lang['website.timezone'], 'Europe/Paris',
			array('description' => $this->lang['website.timezone.explanation'])
		));

		$fieldset = new FormFieldsetHTML('security_config', $admin_user_lang['members.config-security']);
		$this->form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('internal_password_min_length', $admin_user_lang['security.config.internal-password-min-length'], $this->security_config->get_internal_password_min_length(),
			array('min' => 6, 'max' => 30),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'), new FormFieldConstraintIntegerRange(6, 30))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('internal_password_strength', $admin_user_lang['security.config.internal-password-strength'], $this->security_config->get_internal_password_strength(),
			array(
				new FormFieldSelectChoiceOption($admin_user_lang['security.config.internal-password-strength.weak'], SecurityConfig::PASSWORD_STRENGTH_WEAK),
				new FormFieldSelectChoiceOption($admin_user_lang['security.config.internal-password-strength.medium'], SecurityConfig::PASSWORD_STRENGTH_MEDIUM),
				new FormFieldSelectChoiceOption($admin_user_lang['security.config.internal-password-strength.strong'], SecurityConfig::PASSWORD_STRENGTH_STRONG),
				new FormFieldSelectChoiceOption($admin_user_lang['security.config.internal-password-strength.very-strong'], SecurityConfig::PASSWORD_STRENGTH_VERY_STRONG)
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('login_and_email_forbidden_in_password', $admin_user_lang['security.config.login-and-email-forbidden-in-password'], $this->security_config->are_login_and_email_forbidden_in_password(),
			array('class' => 'custom-checkbox')
		));

		if ($this->distribution_config['default_captcha'])
		{
			$fieldset = new FormFieldsetHTML('captcha_config', $this->lang['website.captcha.config']);
			$this->form->add_fieldset($fieldset);

			$default_captcha = $this->distribution_config['default_captcha'];
			$default_captcha::display_config_form_fields($fieldset);
		}

		$action_fieldset = new FormFieldsetSubmit('actions', array('css_class' => 'fieldset-submit next-step'));
		$back = new FormButtonLinkCssImg($this->lang['step.previous'], InstallUrlBuilder::database(), 'fa fa-arrow-left');
		$action_fieldset->add_element($back);
		$this->submit_button = new FormButtonSubmitCssImg($this->lang['step.next'], 'fa fa-arrow-right', 'website');
		$action_fieldset->add_element($this->submit_button);
		$this->form->add_fieldset($action_fieldset);
	}

	private function handle_form()
	{
		$installation_services = new InstallationServices();
		$installation_services->configure_website(
		$this->form->get_value('host'), $this->form->get_value('path'),
		$this->form->get_value('name'), $this->form->get_value('slogan'), $this->form->get_value('description'),
		$this->form->get_value('timezone')->get_raw_value());

		$this->security_config->set_internal_password_min_length($this->form->get_value('internal_password_min_length'));
		$this->security_config->set_internal_password_strength($this->form->get_value('internal_password_strength')->get_raw_value());

		if ($this->form->get_value('login_and_email_forbidden_in_password'))
			$this->security_config->forbid_login_and_email_in_password();
		else
			$this->security_config->allow_login_and_email_in_password();

		SecurityConfig::save();

		$default_captcha = $this->distribution_config['default_captcha'];
		if ($default_captcha)
			$default_captcha::save_config($this->form);

		AppContext::get_response()->redirect(InstallUrlBuilder::admin());
	}

	private function current_server_host()
	{
		return Appcontext::get_request()->get_site_url();
	}

	private function current_server_path()
	{
		$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
		if (!$server_path)
		{
			$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
		}
		$server_path = trim(preg_replace('`/install$`u', '', dirname($server_path)));
		return $server_path = ($server_path == '/') ? '' : $server_path;
	}

	private function warning_if_not_equals(FormField $field, $message)
	{
		$tpl = new StringTemplate('var field = $FF(${escapejs(ID)});
var value = ${escapejs(VALUE)};
if (field.getValue()!=value && !confirm(${escapejs(MESSAGE)})){field.setValue(value);}');
		$tpl->put('ID', $field->get_id());
		$tpl->put('VALUE', $field->get_value());
		$tpl->put('MESSAGE', $message);
		return $tpl->render();
	}

	/**
	 * @param Template $view
	 * @return InstallDisplayResponse
	 */
	private function create_response()
	{
		$this->view = new FileTemplate('install/website.tpl');
		$this->view->put('WEBSITE_FORM', $this->form->display());
		$step_title = $this->lang['step.websiteConfig.title'];
		$default_captcha = $this->distribution_config['default_captcha'];
		$additional_stylesheet = $default_captcha ? $default_captcha::get_css_stylesheet() : '';
		$response = new InstallDisplayResponse(4, $step_title, $this->view, $additional_stylesheet);
		return $response;
	}
}
?>
