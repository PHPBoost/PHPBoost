<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 4.1 - 2014 10 14
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminShoutboxConfigController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('max_messages_number')->set_hidden(!$this->config->is_max_messages_number_enabled());
			$this->form->get_field_by_id('max_links_number_per_message')->set_hidden(!$this->config->is_max_links_number_per_message_enabled());
			$this->form->get_field_by_id('forbidden_formatting_tags')->set_selected_options($this->config->get_forbidden_formatting_tags());
			$this->form->get_field_by_id('refresh_delay')->set_hidden(!$this->config->is_automatic_refresh_enabled());
			$this->form->get_field_by_id('shout_max_messages_number')->set_hidden(!$this->config->is_shout_max_messages_number_enabled());
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new DefaultAdminDisplayResponse($this->view);
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->lang['form.items.per.page'], $this->config->get_items_per_page(),
			array('class' => 'third-field', 'min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldCheckbox('max_messages_number_enabled', $this->lang['shoutbox.enable.max.messages.number'], $this->config->is_max_messages_number_enabled(),
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

		$fieldset->add_field(new FormFieldNumberEditor('max_messages_number', $this->lang['shoutbox.max.messages.number'], $this->config->get_max_messages_number(),
			array(
				'class' => 'third-field', 'min' => 5, 'max' => 1000, 'required' => true,
				'hidden' => !$this->config->is_max_messages_number_enabled()
			),
			array(new FormFieldConstraintIntegerRange(5, 1000))
		));

		$fieldset->add_field(new FormFieldSpacer('1_separator', ''));

		$fieldset->add_field(new FormFieldCheckbox('no_write_authorization_message_displayed', $this->lang['shoutbox.display.no.write.message'], $this->config->is_no_write_authorization_message_displayed(),
			array('class' => 'third-field custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('max_links_number_per_message_enabled', $this->lang['shoutbox.enable.max.links.per.message'], $this->config->is_max_links_number_per_message_enabled(),
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

		$fieldset->add_field(new FormFieldNumberEditor('max_links_number_per_message', $this->lang['shoutbox.max.links.per.message'], $this->config->get_max_links_number_per_message(),
			array('class' => 'third-field', 'min' => 1, 'max' => 20, 'required' => true, 'hidden' => !$this->config->is_max_links_number_per_message_enabled()),
			array(new FormFieldConstraintIntegerRange(1, 20))
		));

		$fieldset->add_field(new FormFieldSpacer('2_separator', ''));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('forbidden_formatting_tags', $this->lang['form.forbidden.tags'], $this->config->get_forbidden_formatting_tags(), $this->generate_forbidden_formatting_tags_option(),
			array('size' => 10)
		));

		$fieldset = new FormFieldsetHTML('configuration', $this->lang['shoutbox.shoutbox.menu']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('date_displayed', $this->lang['shoutbox.display.date'], $this->config->is_date_displayed(),
			array('class' => 'third-field custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('validation_onkeypress_enter_enabled', $this->lang['shoutbox.enable.validation.onkeypress.enter'], $this->config->is_validation_onkeypress_enter_enabled(),
			array('class' => 'third-field custom-checkbox')
		));

		if (ModulesManager::is_module_installed('BBCode') && ModulesManager::is_module_activated('BBCode'))
		{
			$fieldset->add_field(new FormFieldCheckbox('shout_bbcode_enabled', $this->lang['shoutbox.mini.enable.bbcode'], $this->config->is_shout_bbcode_enabled(),
				array('class' => 'third-field custom-checkbox')
			));
		}

		$fieldset->add_field(new FormFieldSpacer('3_separator', ''));

		$fieldset->add_field(new FormFieldCheckbox('automatic_refresh_enabled', $this->lang['shoutbox.enable.automatic.refresh'], $this->config->is_automatic_refresh_enabled(),
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

		$fieldset->add_field(new FormFieldDecimalNumberEditor('refresh_delay', $this->lang['shoutbox.refresh.delay'], $this->config->get_refresh_delay() / 60000,
			array('class' => 'third-field', 'min' => 0, 'max' => 60, 'step' => 0.5, 'description' => $this->lang['shoutbox.refresh.delay.clue'], 'required' => true, 'hidden' => !$this->config->is_automatic_refresh_enabled())
		));

		$fieldset->add_field(new FormFieldSpacer('4_separator', ''));

		$fieldset->add_field(new FormFieldCheckbox('shout_max_messages_number_enabled', $this->lang['shoutbox.mini.enable.max.messages.number'], $this->config->is_shout_max_messages_number_enabled(),
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

		$fieldset->add_field(new FormFieldNumberEditor('shout_max_messages_number', $this->lang['shoutbox.mini.max.messages.number'], $this->config->get_shout_max_messages_number(),
			array('class' => 'third-field', 'min' => 5, 'max' => 1000, 'required' => true, 'hidden' => !$this->config->is_shout_max_messages_number_enabled()),
			array(new FormFieldConstraintIntegerRange(5, 1000))
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['form.authorizations']);

		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['form.authorizations.read'], ShoutboxAuthorizationsService::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['form.authorizations.write'], ShoutboxAuthorizationsService::WRITE_AUTHORIZATIONS),
			new MemberDisabledActionAuthorization($this->lang['form.authorizations.moderation'], ShoutboxAuthorizationsService::MODERATION_AUTHORIZATIONS)
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
		$this->config->set_items_per_page($this->form->get_value('items_per_page'));

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

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
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
