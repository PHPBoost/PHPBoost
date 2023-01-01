<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2012 11 30
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminGuestbookConfigController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('forbidden_tags')->set_selected_options($this->config->get_forbidden_tags());
			$this->form->get_field_by_id('max_links_number_per_message')->set_hidden(!$this->config->is_max_links_number_per_message_enabled());
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

		$fieldset->add_field(new FormFieldMultipleSelectChoice('forbidden_tags', $this->lang['form.forbidden.tags'], $this->config->get_forbidden_tags(), $this->generate_forbidden_tags_option(),
			array('size' => 10)
		));

		$fieldset->add_field(new FormFieldNumberEditor('items_per_page', $this->lang['form.items.per.page'], $this->config->get_items_per_page(),
			array('class' => 'top-field', 'min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));

		$fieldset->add_field(new FormFieldCheckbox('max_links_number_per_message_enabled', $this->lang['guestbook.links.limit.in.item'], $this->config->is_max_links_number_per_message_enabled(),
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

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['form.authorizations.read'], GuestbookAuthorizationsService::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['form.authorizations.write'], GuestbookAuthorizationsService::WRITE_AUTHORIZATIONS),
			new MemberDisabledActionAuthorization($this->lang['form.authorizations.moderation'], GuestbookAuthorizationsService::MODERATION_AUTHORIZATIONS)
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

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
