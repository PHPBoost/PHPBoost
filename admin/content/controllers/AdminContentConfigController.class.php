<?php
/*##################################################
 *                       AdminContentConfigController.class.php
 *                            -------------------
 *   begin                : July 8, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AdminContentConfigController extends AdminController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;
	
	private $content_formatting_config;
	private $content_management_config;
	private $user_accounts_config;
	
	const HTML_USAGE_AUTHORIZATIONS = 1;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('forbidden_tags')->set_selected_options($this->content_formatting_config->get_forbidden_tags());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminContentDisplayResponse($tpl, $this->lang['content.config']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-contents-common');
		$this->content_formatting_config = ContentFormattingConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
		$this->user_accounts_config = UserAccountsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('language-config', $this->lang['content.config.language']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldEditors('formatting_language', $this->lang['content.config.default-formatting-language'], $this->content_formatting_config->get_default_editor(), array (
			'description' => $this->lang['content.config.default-formatting-language-explain'])
		));
		
		$fieldset->add_field(new FormFieldMultipleSelectChoice('forbidden_tags', $this->lang['comments.config.forbidden-tags'], $this->content_formatting_config->get_forbidden_tags(),
			$this->generate_forbidden_tags_option(), array('size' => 10)
		));
		
		$fieldset = new FormFieldsetHTML('html-language-config', $this->lang['content.config.html-language']);
		$form->add_fieldset($fieldset);
		
		$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($this->lang['content.config.html-language-use-authorization'], self::HTML_USAGE_AUTHORIZATIONS, $this->lang['content.config.html-language-use-authorization-explain'])));
		$auth_settings->build_from_auth_array($this->content_formatting_config->get_html_tag_auth());
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset->add_field($auth_setter);
		
		$fieldset = new FormFieldsetHTML('post-management', $this->lang['content.config.post-management']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldNumberEditor('max_pm_number', $this->lang['content.config.max-pm-number'], $this->user_accounts_config->get_max_private_messages_number(),
			array('required' => true, 'description' => $this->lang['content.config.max-pm-number-explain']),
			array(new FormFieldConstraintRegex('`^([0-9]+)$`i', '', LangLoader::get_message('form.doesnt_match_number_regex', 'status-messages-common')))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('anti_flood_enabled', $this->lang['content.config.anti-flood-enabled'], $this->content_management_config->is_anti_flood_enabled(),
			array('description' => $this->lang['content.config.anti-flood-enabled-explain'])
		));
		
		$fieldset->add_field(new FormFieldNumberEditor('delay_flood', $this->lang['content.config.delay-flood'], $this->content_management_config->get_anti_flood_duration(), array(
			'required' => true, 'description' => $this->lang['content.config.delay-flood-explain']),
			array(new FormFieldConstraintRegex('`^([0-9]+)$`i', '', LangLoader::get_message('form.doesnt_match_number_regex', 'status-messages-common')))
		));
		
		$fieldset = new FormFieldsetHTML('captcha', $this->lang['content.config.captcha']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('captcha_used', $this->lang['content.config.captcha-used'], $this->content_management_config->get_used_captcha_module(),
			$this->generate_captcha_available_option(), array('description' => $this->lang['content.config.captcha-used-explain'])
		));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}

	private function save()
	{
		$this->content_formatting_config->set_default_editor($this->form->get_value('formatting_language')->get_raw_value());
		$this->content_formatting_config->set_html_tag_auth($this->form->get_value('authorizations')->build_auth_array());
		$forbidden_tags = array();
		foreach ($this->form->get_value('forbidden_tags') as $field => $option)
		{
			$forbidden_tags[] = $option->get_raw_value();
		}
	 	$this->content_formatting_config->set_forbidden_tags($forbidden_tags);
		ContentFormattingConfig::save();
		
		if ($this->form->get_value('anti_flood_enabled'))
			$this->content_management_config->set_anti_flood_enabled(true);
		else
			$this->content_management_config->set_anti_flood_enabled(false);
		
		$this->content_management_config->set_anti_flood_duration($this->form->get_value('delay_flood'));
		$this->content_management_config->set_used_captcha_module($this->form->get_value('captcha_used')->get_raw_value());
		ContentManagementConfig::save();
		
		$this->user_accounts_config->set_max_private_messages_number($this->form->get_value('max_pm_number'));
		UserAccountsConfig::save();
	}
	
	private function generate_forbidden_tags_option()
	{
		$options = array();
		$available_tags = AppContext::get_content_formatting_service()->get_available_tags();
		foreach ($available_tags as $identifier => $name)
		{
			$options[] = new FormFieldSelectChoiceOption($name, $identifier);
		}
		return $options;
	}
	
	private function generate_captcha_available_option()
	{
		$options = array();
		$captchas = AppContext::get_captcha_service()->get_available_captchas();
		foreach ($captchas as $identifier => $name)
		{
			$options[] = new FormFieldSelectChoiceOption($name, $identifier);
		}
		return $options;
	}
}
?>