<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 07 31
 * @since       PHPBoost 5.1 - 2017 09 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminSandboxConfigController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $lang;

	/**
	 * @var GoogleMapsConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			$this->form->get_field_by_id('superadmin_name')->set_hidden(!$this->config->get_superadmin_enabled());

			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminSandboxDisplayResponse($tpl, $this->lang['mini.config.title']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('config', 'sandbox');
		$this->config = SandboxConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('config', $this->lang['mini.config.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('superadmin_enabled', $this->lang['mini.superadmin.enabled'], $this->config->get_superadmin_enabled(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("superadmin_enabled").getValue()) {
						HTMLForms.getField("superadmin_name").enable();
					} else {
						HTMLForms.getField("superadmin_name").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldAjaxSearchUserAutoComplete('superadmin_name', $this->lang['mini.superadmin.id'], $this->config->get_superadmin_name(),
			array('hidden' => !$this->config->get_superadmin_enabled()),
			array(new SandboxConstraintUserIsAdmin)
		));

		$fieldset->add_field(new FormFieldRadioChoice('open_menu', $this->lang['mini.open.menu'], $this->config->get_open_menu(),
			array(
				new FormFieldRadioChoiceOption($this->lang['mini.open.menu.left'], SandboxConfig::LEFT_MENU),
				new FormFieldRadioChoiceOption($this->lang['mini.open.menu.right'], SandboxConfig::RIGHT_MENU)
			),
			array('class' => 'inline-radio custom-radio')
		));

		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $common_lang['authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['config.authorizations.read'], SandboxAuthorizationsService::READ_AUTHORIZATIONS)
		));

		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->set_superadmin_enabled($this->form->get_value('superadmin_enabled'));
		$this->config->set_superadmin_name($this->form->get_value('superadmin_name'));
		$this->config->set_open_menu($this->form->get_value('open_menu')->get_raw_value());

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		SandboxConfig::save();
	}
}
?>
