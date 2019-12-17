<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 18
 * @since       PHPBoost 4.0 - 2013 07 08
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminContentConfigController extends AdminController
{
	private $lang;
	private $admin_common_lang;
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
			$this->form->get_field_by_id('new_content_duration')->set_hidden(!$this->content_management_config->is_new_content_enabled());
			$this->form->get_field_by_id('new_content_unauthorized_modules')->set_hidden(!$this->content_management_config->is_new_content_enabled());
			$this->form->get_field_by_id('new_content_unauthorized_modules')->set_selected_options($this->content_management_config->get_new_content_unauthorized_modules());
			$this->form->get_field_by_id('notation_scale')->set_hidden(!$this->content_management_config->is_notation_enabled());
			$this->form->get_field_by_id('notation_unauthorized_modules')->set_hidden(!$this->content_management_config->is_notation_enabled());
			$this->form->get_field_by_id('notation_unauthorized_modules')->set_selected_options($this->content_management_config->get_notation_unauthorized_modules());
			$this->form->get_field_by_id('content_sharing_email_enabled')->set_hidden(!$this->content_management_config->is_content_sharing_enabled());
			$this->form->get_field_by_id('content_sharing_print_enabled')->set_hidden(!$this->content_management_config->is_content_sharing_enabled());
			$this->form->get_field_by_id('content_sharing_sms_enabled')->set_hidden(!$this->content_management_config->is_content_sharing_enabled());
			$this->form->get_field_by_id('site_default_picture_url')->set_hidden(!$this->content_management_config->is_opengraph_enabled());
			$this->form->get_field_by_id('id_card_unauthorized_modules')->set_hidden(!$this->content_management_config->is_id_card_enabled());
			$this->form->get_field_by_id('id_card_unauthorized_modules')->set_selected_options($this->content_management_config->get_id_card_unauthorized_modules());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminContentDisplayResponse($tpl, $this->lang['content.config']);
	}

	private function init()
	{
		$this->lang                      = LangLoader::get('admin-contents-common');
		$this->admin_common_lang         = LangLoader::get('admin-common');
		$this->content_formatting_config = ContentFormattingConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
		$this->user_accounts_config      = UserAccountsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('language-config', $this->lang['content.config.language']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldEditors('formatting_language', $this->lang['content.config.default-formatting-language'], $this->content_formatting_config->get_default_editor(),
			array ('class' => 'top-field', 'description' => $this->lang['content.config.default-formatting-language-explain'])
		));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('forbidden_tags', $this->lang['comments.config.forbidden-tags'], $this->content_formatting_config->get_forbidden_tags(),
			$this->generate_forbidden_tags_option(), array('size' => 10)
		));

		$fieldset = new FormFieldsetHTML('html-language-config', $this->lang['content.config.html-language']);
		$form->add_fieldset($fieldset);

		$auth_settings = new AuthorizationsSettings(array(new VisitorDisabledActionAuthorization($this->lang['content.config.html-language-use-authorization'], self::HTML_USAGE_AUTHORIZATIONS, $this->lang['content.config.html-language-use-authorization-explain'])));
		$auth_settings->build_from_auth_array($this->content_formatting_config->get_html_tag_auth());
		$fieldset->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$fieldset = new FormFieldsetHTML('post-management', $this->lang['content.config.post-management']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldNumberEditor('max_pm_number', $this->lang['content.config.max-pm-number'], $this->user_accounts_config->get_max_private_messages_number(),
			array('required' => true, 'description' => $this->lang['content.config.max-pm-number-explain']),
			array(new FormFieldConstraintRegex('`^([0-9]+)$`iu', '', LangLoader::get_message('form.doesnt_match_number_regex', 'status-messages-common')))
		));

		$fieldset->add_field(new FormFieldCheckbox('anti_flood_enabled', $this->lang['content.config.anti-flood-enabled'], $this->content_management_config->is_anti_flood_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['content.config.anti-flood-enabled-explain']
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('delay_flood', $this->lang['content.config.delay-flood'], $this->content_management_config->get_anti_flood_duration(), array(
			'required' => true, 'description' => $this->lang['content.config.delay-flood-explain']),
			array(new FormFieldConstraintRegex('`^([0-9]+)$`iu', '', LangLoader::get_message('form.doesnt_match_number_regex', 'status-messages-common')))
		));

		$fieldset = new FormFieldsetHTML('captcha', $this->lang['content.config.captcha']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('captcha_used', $this->lang['content.config.captcha-used'], $this->content_management_config->get_used_captcha_module(),
			$this->generate_captcha_available_option(), array('description' => $this->lang['content.config.captcha-used-explain'])
		));

		$fieldset = new FormFieldsetHTML('tagnew_config', $this->lang['content.config.new-content-config']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('new_content_enabled', $this->lang['content.config.new-content'], $this->content_management_config->is_new_content_enabled(),
			array(
				'class' => 'top-field custom-checkbox',
				'description' => $this->lang['content.config.new-content-explain'],
				'events' => array('click' => '
					if (HTMLForms.getField("new_content_enabled").getValue()) {
						HTMLForms.getField("new_content_duration").enable();
						HTMLForms.getField("new_content_unauthorized_modules").enable();
					} else {
						HTMLForms.getField("new_content_duration").disable();
						HTMLForms.getField("new_content_unauthorized_modules").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('new_content_duration', $this->lang['content.config.new-content-duration'], $this->content_management_config->get_new_content_duration(),
			array('class' => 'top-field', 'min' => 1, 'required' => true, 'description' => $this->lang['content.config.new-content-duration-explain'], 'hidden' => !$this->content_management_config->is_new_content_enabled()),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'), new FormFieldConstraintIntegerRange(1, 9999))
		));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('new_content_unauthorized_modules', $this->admin_common_lang['config.forbidden-module'], $this->content_management_config->get_new_content_unauthorized_modules(), ModulesManager::generate_unauthorized_module_option('newcontent'),
			array('size' => 12, 'description' => $this->admin_common_lang['config.new-content.forbidden-module-explain'], 'hidden' => !$this->content_management_config->is_new_content_enabled())
		));

		$fieldset = new FormFieldsetHTML('notation_config', $this->lang['notation.config']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('notation_enabled', $this->admin_common_lang['config.notation_enabled'], $this->content_management_config->is_notation_enabled(),
			array(
				'class' => 'top-field custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("notation_enabled").getValue()) {
						HTMLForms.getField("notation_scale").enable();
						HTMLForms.getField("notation_unauthorized_modules").enable();
					} else {
						HTMLForms.getField("notation_scale").disable();
						HTMLForms.getField("notation_unauthorized_modules").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('notation_scale', $this->admin_common_lang['config.notation_scale'], $this->content_management_config->get_notation_scale(),
			array('class' => 'top-field', 'min' => 3, 'max' => 20, 'required' => true, 'hidden' => !$this->content_management_config->is_notation_enabled()),
			array(new FormFieldConstraintIntegerRange(3, 20))
		));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('notation_unauthorized_modules', $this->admin_common_lang['config.forbidden-module'], $this->content_management_config->get_notation_unauthorized_modules(), ModulesManager::generate_unauthorized_module_option('notation'),
			array('size' => 6, 'description' => $this->admin_common_lang['config.notation.forbidden-module-explain'], 'hidden' => !$this->content_management_config->is_notation_enabled())
		));

		$fieldset = new FormFieldsetHTML('sharing_config', $this->lang['content.config.sharing']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('content_sharing_enabled', $this->lang['content.config.content-sharing-enabled'], $this->content_management_config->is_content_sharing_enabled(),
			array(
				'class' => 'top-field custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("content_sharing_enabled").getValue()) {
						HTMLForms.getField("content_sharing_email_enabled").enable();
						HTMLForms.getField("content_sharing_print_enabled").enable();
						HTMLForms.getField("content_sharing_sms_enabled").enable();
					} else {
						HTMLForms.getField("content_sharing_email_enabled").disable();
						HTMLForms.getField("content_sharing_print_enabled").disable();
						HTMLForms.getField("content_sharing_sms_enabled").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('content_sharing_email_enabled', $this->lang['content.config.content-sharing-email-enabled'], $this->content_management_config->is_content_sharing_email_enabled(),
			array(
				'class' => 'custom-checkbox',
				'hidden' => !$this->content_management_config->is_content_sharing_enabled()
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('content_sharing_print_enabled', $this->lang['content.config.content-sharing-print-enabled'], $this->content_management_config->is_content_sharing_print_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['content.config.content-sharing-print-enabled.explain'], 'hidden' => !$this->content_management_config->is_content_sharing_enabled()
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('content_sharing_sms_enabled', $this->lang['content.config.content-sharing-sms-enabled'], $this->content_management_config->is_content_sharing_sms_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['content.config.content-sharing-sms-enabled.explain'],
				'hidden' => !$this->content_management_config->is_content_sharing_enabled()
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('opengraph_enabled', $this->lang['content.config.opengraph-enabled'], $this->content_management_config->is_opengraph_enabled(),
			array(
				'class' => 'top-field custom-checkbox',
				'description' => $this->lang['content.config.opengraph-enabled.explain'],
				'events' => array('click' => '
					if (HTMLForms.getField("opengraph_enabled").getValue()) {
						HTMLForms.getField("site_default_picture_url").enable();
						jQuery("#' . __CLASS__ . '_site_default_picture_url_preview").show();
					} else {
						HTMLForms.getField("site_default_picture_url").disable();
						jQuery("#' . __CLASS__ . '_site_default_picture_url_preview").hide();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldUploadPictureFile('site_default_picture_url', $this->lang['content.config.site-default-picture-url'], $this->content_management_config->get_site_default_picture_url()->relative(),
			array(
				'class' => 'top-field',
				'hidden' => !$this->content_management_config->is_opengraph_enabled()
			)
		));

		$fieldset = new FormFieldsetHTML('id_card_config', $this->lang['content.config.id.card']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('id_card_enabled', $this->lang['content.config.id.card.enabled'], $this->content_management_config->is_id_card_enabled(),
			array(
				'class' => 'top-field custom-checkbox',
				'description' => $this->lang['content.config.id.card.explain'],
				'events' => array('click' => '
					if (HTMLForms.getField("id_card_enabled").getValue()) {
						HTMLForms.getField("id_card_unauthorized_modules").enable();
					} else {
						HTMLForms.getField("id_card_unauthorized_modules").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldMultipleSelectChoice('id_card_unauthorized_modules', $this->admin_common_lang['config.forbidden-module'], $this->content_management_config->get_id_card_unauthorized_modules(), ModulesManager::generate_unauthorized_module_option('idcard'),
			array(
				'size' => 6,
				'description' => $this->lang['config.id.card.forbidden-module-explain'],
				'hidden' => !$this->content_management_config->is_id_card_enabled()
			)
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

		if ($this->form->get_value('new_content_enabled'))
		{
			$this->content_management_config->set_new_content_enabled(true);
			$this->content_management_config->set_new_content_duration($this->form->get_value('new_content_duration'));

			$unauthorized_modules = array();
			foreach ($this->form->get_value('new_content_unauthorized_modules') as $field => $option)
			{
				$unauthorized_modules[] = $option->get_raw_value();
			}
			$this->content_management_config->set_new_content_unauthorized_modules($unauthorized_modules);
		}
		else
			$this->content_management_config->set_new_content_enabled(false);


		if ($this->form->get_value('notation_enabled'))
		{
			$this->content_management_config->set_notation_enabled(true);
			if ($this->form->get_value('notation_scale') != $this->content_management_config->get_notation_scale())
			{
				foreach (ModulesManager::get_activated_feature_modules('notation') as $module_id => $module)
				{
					NotationService::update_notation_scale($module_id, $this->content_management_config->get_notation_scale(), $this->form->get_value('notation_scale'));
				}
				$this->content_management_config->set_notation_scale($this->form->get_value('notation_scale'));
			}

			$unauthorized_modules = array();
			foreach ($this->form->get_value('notation_unauthorized_modules') as $field => $option)
			{
				$unauthorized_modules[] = $option->get_raw_value();
			}
			$this->content_management_config->set_notation_unauthorized_modules($unauthorized_modules);
		}
		else
			$this->content_management_config->set_notation_enabled(false);

		if ($this->form->get_value('content_sharing_enabled'))
		{
			$this->content_management_config->set_content_sharing_enabled(true);

			if ($this->form->get_value('content_sharing_email_enabled'))
				$this->content_management_config->set_content_sharing_email_enabled(true);
			else
				$this->content_management_config->set_content_sharing_email_enabled(false);

			if ($this->form->get_value('content_sharing_print_enabled'))
				$this->content_management_config->set_content_sharing_print_enabled(true);
			else
				$this->content_management_config->set_content_sharing_print_enabled(false);

			if ($this->form->get_value('content_sharing_sms_enabled'))
				$this->content_management_config->set_content_sharing_sms_enabled(true);
			else
				$this->content_management_config->set_content_sharing_sms_enabled(false);
		}
		else
			$this->content_management_config->set_content_sharing_enabled(false);

		if ($this->form->get_value('opengraph_enabled'))
		{
			$this->content_management_config->set_opengraph_enabled(true);
			$this->content_management_config->set_site_default_picture_url($this->form->get_value('site_default_picture_url'));
		}
		else
			$this->content_management_config->set_opengraph_enabled(false);

		if ($this->form->get_value('id_card_enabled'))
		{
			$this->content_management_config->set_id_card_enabled(true);

			$unauthorized_modules = array();
			foreach ($this->form->get_value('id_card_unauthorized_modules') as $field => $option)
			{
				$unauthorized_modules[] = $option->get_raw_value();
			}
			$this->content_management_config->set_id_card_unauthorized_modules($unauthorized_modules);
		}
		else
			$this->content_management_config->set_id_card_enabled(false);

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
