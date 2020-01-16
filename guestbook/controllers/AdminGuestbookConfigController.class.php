<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 16
 * @since       PHPBoost 3.0 - 2012 11 30
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminGuestbookConfigController extends AdminModuleController
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
	private $admin_common_lang;

	/**
	 * @var GuestbookConfig
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
			$this->form->get_field_by_id('forbidden_tags')->set_selected_options($this->config->get_forbidden_tags());
			$this->form->get_field_by_id('max_links_number_per_message')->set_hidden(!$this->config->is_max_links_number_per_message_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($tpl);
	}

	private function init()
	{
		$this->config = GuestbookConfig::load();
		$this->lang = LangLoader::get('common', 'guestbook');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('configuration', StringVars::replace_vars(LangLoader::get_message('configuration.module.title', 'admin-common'), array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldMultipleSelectChoice('forbidden_tags', $this->admin_common_lang['config.forbidden-tags'], $this->config->get_forbidden_tags(), $this->generate_forbidden_tags_option(),
			array('size' => 10)
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->admin_common_lang['config.items_number_per_page'], $this->config->get_items_per_page(),
			array('class' => 'top-field', 'min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldCheckbox('max_links_number_per_message_enabled', $this->lang['guestbook.max.links.number.per.message.enabled'], $this->config->is_max_links_number_per_message_enabled(),
			array(
				'class' => 'top-field custom-checkbox', 
				'events' => array('click' => '
					if (HTMLForms.getField("max_links_number_per_message_enabled").getValue()) {
							HTMLForms.getField("max_links_number_per_message").enable();
					} else {
							HTMLForms.getField("max_links_number_per_message").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldNumberEditor('max_links_number_per_message', $this->lang['guestbook.max.links'], $this->config->get_maximum_links_message(),
			array('class' => 'top-field', 'min' => 1, 'max' => 20, 'required' => true, 'hidden' => !$this->config->is_max_links_number_per_message_enabled()),
			array(new FormFieldConstraintIntegerRange(1, 20))
		));

		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $common_lang['authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['admin.authorizations.read'], GuestbookAuthorizationsService::READ_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.write'], GuestbookAuthorizationsService::WRITE_AUTHORIZATIONS),
			new MemberDisabledActionAuthorization($common_lang['authorizations.moderation'], GuestbookAuthorizationsService::MODERATION_AUTHORIZATIONS)
		));

		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function generate_forbidden_tags_option()
	{
		$options = array();
		foreach (AppContext::get_content_formatting_service()->get_available_tags() as $identifier => $name)
		{
			$options[] = new FormFieldSelectChoiceOption($name, $identifier);
		}
		return $options;
	}

	private function save()
	{
		$this->config->set_items_per_page($this->form->get_value('items_per_page'));

		$forbidden_tags = array();
		foreach ($this->form->get_value('forbidden_tags') as $field => $option)
		{
			$forbidden_tags[] = $option->get_raw_value();
		}

		$this->config->set_forbidden_tags($forbidden_tags);

		if ($this->form->get_value('max_links_number_per_message_enabled'))
		{
			$this->config->enable_max_links_number_per_message();
			$this->config->set_maximum_links_message($this->form->get_value('max_links_number_per_message'));
		}
		else
			$this->config->disable_max_links_number_per_message();

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		GuestbookConfig::save();
		GuestbookCache::invalidate();
	}
}
?>
