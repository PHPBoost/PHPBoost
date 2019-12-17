<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 05
 * @since       PHPBoost 4.1 - 2014 10 14
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class AdminShoutboxConfigController extends AdminModuleController
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
	 * @var ShoutboxConfig
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
			$this->form->get_field_by_id('max_messages_number')->set_hidden(!$this->config->is_max_messages_number_enabled());
			$this->form->get_field_by_id('max_links_number_per_message')->set_hidden(!$this->config->is_max_links_number_per_message_enabled());
			$this->form->get_field_by_id('forbidden_formatting_tags')->set_selected_options($this->config->get_forbidden_formatting_tags());
			$this->form->get_field_by_id('refresh_delay')->set_hidden(!$this->config->is_automatic_refresh_enabled());
			$this->form->get_field_by_id('shout_max_messages_number')->set_hidden(!$this->config->is_shout_max_messages_number_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminShoutboxDisplayResponse($tpl, $this->lang['module_config_title']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'shoutbox');
		$this->config = ShoutboxConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('configuration', LangLoader::get_message('configuration', 'admin-common'));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('items_number_per_page', $this->lang['config.items_number_per_page'], $this->config->get_items_number_per_page(),
			array('class' => 'third-field', 'min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldCheckbox('max_messages_number_enabled', $this->lang['config.max_messages_number_enabled'], $this->config->is_max_messages_number_enabled(),
			array(
				'class' => 'third-field custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("max_messages_number_enabled").getValue()) {
						HTMLForms.getField("max_messages_number").enable();
					} else {
						HTMLForms.getField("max_messages_number").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('max_messages_number', $this->lang['config.max_messages_number'], $this->config->get_max_messages_number(),
			array('class' => 'third-field', 'min' => 5, 'max' => 1000, 'required' => true, 'hidden' => !$this->config->is_max_messages_number_enabled()),
			array(new FormFieldConstraintIntegerRange(5, 1000))
		));

		$fieldset->add_field(new FormFieldFree('1_separator', '', ''));

		$fieldset->add_field(new FormFieldCheckbox('no_write_authorization_message_displayed', $this->lang['config.no_write_authorization_message_displayed'], $this->config->is_no_write_authorization_message_displayed(),
			array('class' => 'third-field custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('max_links_number_per_message_enabled', $this->lang['config.max_links_number_per_message_enabled'], $this->config->is_max_links_number_per_message_enabled(),
			array(
				'class' => 'third-field custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("max_links_number_per_message_enabled").getValue()) {
						HTMLForms.getField("max_links_number_per_message").enable();
					} else {
						HTMLForms.getField("max_links_number_per_message").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('max_links_number_per_message', $this->lang['config.max_links_number_per_message'], $this->config->get_max_links_number_per_message(),
			array('class' => 'third-field', 'min' => 1, 'max' => 20, 'required' => true, 'hidden' => !$this->config->is_max_links_number_per_message_enabled()),
			array(new FormFieldConstraintIntegerRange(1, 20))
		));

		$fieldset->add_field(new FormFieldFree('2_separator', '', ''));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('forbidden_formatting_tags', LangLoader::get_message('config.forbidden-tags', 'admin-common'), $this->config->get_forbidden_formatting_tags(), $this->generate_forbidden_formatting_tags_option(),
			array('size' => 10)
		));

		$fieldset = new FormFieldsetHTML('configuration', $this->lang['config.shoutbox_menu']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('date_displayed', $this->lang['config.date_displayed'], $this->config->is_date_displayed(),
			array('class' => 'third-field custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('validation_onkeypress_enter_enabled', $this->lang['config.validation_onkeypress_enter_enabled'], $this->config->is_validation_onkeypress_enter_enabled(),
			array('class' => 'third-field custom-checkbox')
		));

		if (ModulesManager::is_module_installed('BBCode') && ModulesManager::is_module_activated('BBCode'))
		{
			$fieldset->add_field(new FormFieldCheckbox('shout_bbcode_enabled', $this->lang['config.shout_bbcode_enabled'], $this->config->is_shout_bbcode_enabled(),
				array('class' => 'third-field custom-checkbox')
			));
		}

		$fieldset->add_field(new FormFieldFree('3_separator', '', ''));

		$fieldset->add_field(new FormFieldCheckbox('automatic_refresh_enabled', $this->lang['config.automatic_refresh_enabled'], $this->config->is_automatic_refresh_enabled(),
			array(
				'class' => 'third-field custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("automatic_refresh_enabled").getValue()) {
						HTMLForms.getField("refresh_delay").enable();
					} else {
						HTMLForms.getField("refresh_delay").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldDecimalNumberEditor('refresh_delay', $this->lang['config.refresh_delay'], $this->config->get_refresh_delay() / 60000,
			array('class' => 'third-field', 'min' => 0, 'max' => 60, 'step' => 0.5, 'description' => $this->lang['config.refresh_delay.explain'], 'required' => true, 'hidden' => !$this->config->is_automatic_refresh_enabled())
		));

		$fieldset->add_field(new FormFieldFree('4_separator', '', ''));

		$fieldset->add_field(new FormFieldCheckbox('shout_max_messages_number_enabled', $this->lang['config.shout_max_messages_number_enabled'], $this->config->is_shout_max_messages_number_enabled(),
			array(
				'class' => 'third-field custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("shout_max_messages_number_enabled").getValue()) {
						HTMLForms.getField("shout_max_messages_number").enable();
					} else {
						HTMLForms.getField("shout_max_messages_number").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('shout_max_messages_number', $this->lang['config.shout_max_messages_number'], $this->config->get_shout_max_messages_number(),
			array('class' => 'third-field', 'min' => 5, 'max' => 1000, 'required' => true, 'hidden' => !$this->config->is_shout_max_messages_number_enabled()),
			array(new FormFieldConstraintIntegerRange(5, 1000))
		));

		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $common_lang['authorizations']);

		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($common_lang['authorizations.read'], ShoutboxAuthorizationsService::READ_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.write'], ShoutboxAuthorizationsService::WRITE_AUTHORIZATIONS),
			new MemberDisabledActionAuthorization($common_lang['authorizations.moderation'], ShoutboxAuthorizationsService::MODERATION_AUTHORIZATIONS)
		));

		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field($auth_setter);

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->set_items_number_per_page($this->form->get_value('items_number_per_page'));

		if ($this->form->get_value('max_messages_number_enabled'))
		{
			$this->config->enable_max_messages_number();
			$this->config->set_max_messages_number($this->form->get_value('max_messages_number'));
		}
		else
			$this->config->disable_max_messages_number();

		$this->config->set_max_messages_number($this->form->get_value('max_messages_number'));

		if ($this->form->get_value('max_links_number_per_message_enabled'))
		{
			$this->config->enable_max_links_number_per_message();
			$this->config->set_max_links_number_per_message($this->form->get_value('max_links_number_per_message'));
		}
		else
			$this->config->disable_max_links_number_per_message();

		if ($this->form->get_value('no_write_authorization_message_displayed'))
			$this->config->display_no_write_authorization_message();
		else
			$this->config->hide_no_write_authorization_message();

		$forbidden_formatting_tags = array();
		foreach ($this->form->get_value('forbidden_formatting_tags') as $field => $option)
		{
			$forbidden_formatting_tags[] = $option->get_raw_value();
		}

	 	$this->config->set_forbidden_formatting_tags($forbidden_formatting_tags);

		if ($this->form->get_value('automatic_refresh_enabled'))
		{
			$this->config->enable_automatic_refresh();
			$this->config->set_refresh_delay($this->form->get_value('refresh_delay') * 60000);
		}
		else
			$this->config->disable_automatic_refresh();

		if ($this->form->get_value('date_displayed'))
			$this->config->display_date();
		else
			$this->config->hide_date();

		if ($this->form->get_value('shout_max_messages_number_enabled'))
		{
			$this->config->enable_shout_max_messages_number();
			$this->config->set_shout_max_messages_number($this->form->get_value('shout_max_messages_number'));
		}
		else
			$this->config->disable_shout_max_messages_number();

		if (ModulesManager::is_module_installed('BBCode') && ModulesManager::is_module_activated('BBCode'))
		{
			if ($this->form->get_value('shout_bbcode_enabled'))
				$this->config->enable_shout_bbcode();
			else
				$this->config->disable_shout_bbcode();
		}

		if ($this->form->get_value('validation_onkeypress_enter_enabled'))
			$this->config->enable_validation_onkeypress_enter();
		else
			$this->config->disable_validation_onkeypress_enter();

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		ShoutboxConfig::save();
	}

	private function generate_forbidden_formatting_tags_option()
	{
		$options = array();
		$available_tags = AppContext::get_content_formatting_service()->get_available_tags();
		foreach ($available_tags as $identifier => $name)
		{
			$options[] = new FormFieldSelectChoiceOption($name, $identifier);
		}
		return $options;
	}
}
?>
