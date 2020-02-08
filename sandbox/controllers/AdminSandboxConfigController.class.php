<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 08
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
	private $admin_lang;

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
		$tpl->add_lang($this->admin_lang);

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
		$this->admin_lang = LangLoader::get('admin');
		$this->config = SandboxConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('config', $this->lang['mini.config.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('menu_opening_type', $this->admin_lang['push.menu.opening.type'], $this->config->get_menu_opening_type(),
			array(
				new FormFieldSelectChoiceOption($this->admin_lang['push.menu.opening.type.top'], SandboxConfig::TOP_MENU, array('data_option_icon' => 'fa fa-arrow-down')),
				new FormFieldSelectChoiceOption($this->admin_lang['push.menu.opening.type.right'], SandboxConfig::RIGHT_MENU, array('data_option_icon' => 'fa fa-arrow-left')),
				new FormFieldSelectChoiceOption($this->admin_lang['push.menu.opening.type.bottom'], SandboxConfig::BOTTOM_MENU, array('data_option_icon' => 'fa fa-arrow-up')),
				new FormFieldSelectChoiceOption($this->admin_lang['push.menu.opening.type.left'], SandboxConfig::LEFT_MENU, array('data_option_icon' => 'fa fa-arrow-right'))
			),
			array('select_to_list' => true)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('expansion_type', $this->admin_lang['push.menu.expansion.type'], $this->config->get_expansion_type(),
			array(
				new FormFieldSelectChoiceOption($this->admin_lang['push.menu.expansion.type.overlap'], SandboxConfig::OVERLAP, array('data_option_icon' => 'fa fa-sign-in-alt')),
				new FormFieldSelectChoiceOption($this->admin_lang['push.menu.expansion.type.expand'], SandboxConfig::EXPANSION, array('data_option_icon' => 'fa fa-chevron-down')),
				new FormFieldSelectChoiceOption($this->admin_lang['push.menu.expansion.type.none'], SandboxConfig::NO_EXPANSION, array('data_option_icon' => 'fa fa-times-circle'))
			),
			array('select_to_list' => true)
		));

		$fieldset->add_field(new FormFieldCheckbox('disabled_body', $this->admin_lang['push.menu.disabled.body'], $this->config->get_disabled_body(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('pushed_content', $this->admin_lang['push.menu.pushed.content'], $this->config->get_pushed_content(),
			array('class' => 'custom-checkbox')
		));

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
		$this->config->set_menu_opening_type($this->form->get_value('menu_opening_type')->get_raw_value());
		$this->config->set_expansion_type($this->form->get_value('expansion_type')->get_raw_value());
		$this->config->set_disabled_body($this->form->get_value('disabled_body'));
		$this->config->set_pushed_content($this->form->get_value('pushed_content'));

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		SandboxConfig::save();
	}
}
?>
