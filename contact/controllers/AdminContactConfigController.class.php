<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 4.0 - 2013 03 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminContactConfigController extends DefaultAdminModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('informations_position')->set_hidden(!$this->config->are_informations_enabled());
			$this->form->get_field_by_id('informations')->set_hidden(!$this->config->are_informations_enabled());
			$this->form->get_field_by_id('date_in_tracking_number_enabled')->set_hidden(!$this->config->is_tracking_number_enabled());
			if ($this->config->is_googlemaps_available())
			{
				$this->form->get_field_by_id('map_position')->set_hidden(!$this->config->is_map_enabled());
				$this->form->get_field_by_id('map_markers')->set_hidden(!$this->config->is_map_enabled());
			}
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminContactDisplayResponse($this->view, StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['contact.form.title'], $this->config->get_title(),
			array('maxlength' => 255, 'required' => true)
		));

		$fieldset->add_field(new FormFieldCheckbox('sender_acknowledgment_enabled', $this->lang['contact.sender.acknowledgment.enabled'], $this->config->is_sender_acknowledgment_enabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('tracking_number_enabled', $this->lang['contact.tracking.number.enabled'], $this->config->is_tracking_number_enabled(),
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("tracking_number_enabled").getValue()) {
						HTMLForms.getField("date_in_tracking_number_enabled").enable();
					} else {
						HTMLForms.getField("date_in_tracking_number_enabled").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('date_in_tracking_number_enabled', $this->lang['contact.date.in.tracking.number.enabled'], $this->config->is_date_in_tracking_number_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['contact.date.in.tracking.number.clue'],
				'hidden' => !$this->config->is_tracking_number_enabled()
			)
		));

		$fieldset->add_field(new FormFieldSpacer('1_separator', ''));

		$fieldset->add_field(new FormFieldCheckbox('informations_enabled', $this->lang['contact.informations.enabled'], $this->config->are_informations_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['contact.informations.clue'],
				'events' => array('click' => '
					if (HTMLForms.getField("informations_enabled").getValue()) {
						HTMLForms.getField("informations_position").enable();
						HTMLForms.getField("informations").enable();
					} else {
						HTMLForms.getField("informations_position").disable();
						HTMLForms.getField("informations").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('informations_position', $this->lang['contact.informations.position'], $this->config->get_informations_position(),
			array(
				new FormFieldSelectChoiceOption($this->lang['contact.informations.position.left'], ContactConfig::LEFT),
				new FormFieldSelectChoiceOption($this->lang['contact.informations.position.top'], ContactConfig::TOP),
				new FormFieldSelectChoiceOption($this->lang['contact.informations.position.right'], ContactConfig::RIGHT),
				new FormFieldSelectChoiceOption($this->lang['contact.informations.position.bottom'], ContactConfig::BOTTOM),
				),
			array('hidden' => !$this->config->are_informations_enabled())
		));

		$fieldset->add_field(new FormFieldRichTextEditor('informations', $this->lang['contact.informations.content'],
			FormatingHelper::unparse($this->config->get_informations()),
			array(
				'rows' => 8, 'cols' => 47,
				'hidden' => !$this->config->are_informations_enabled()
			)
		));

		if ($this->config->is_googlemaps_available())
		{
			$map_fieldset = new FormFieldsetHTML('map', $this->lang['contact.map.location'], array('disabled' => !$this->config->is_googlemaps_available()));
			$form->add_fieldset($map_fieldset);

			$map_fieldset->add_field(new FormFieldCheckbox('map_enabled', $this->lang['contact.map.enabled'], $this->config->is_map_enabled(),
				array(
					'class' => 'top-field custom-checkbox',
					'hidden' => !$this->config->is_googlemaps_available(),
					'events' => array('click' => '
						if (HTMLForms.getField("map_enabled").getValue()) {
							HTMLForms.getField("map_position").enable();
							HTMLForms.getField("map_markers").enable();
						} else {
							HTMLForms.getField("map_position").disable();
							HTMLForms.getField("map_markers").disable();
						}'
					)
				)
			));

			$map_fieldset->add_field(new FormFieldSimpleSelectChoice('map_position', $this->lang['contact.map.position'], $this->config->get_map_position(),
				array(
					new FormFieldSelectChoiceOption($this->lang['contact.map.position.top'], ContactConfig::MAP_TOP),
					new FormFieldSelectChoiceOption($this->lang['contact.map.position.bottom'], ContactConfig::MAP_BOTTOM),
					),
				array(
					'class' => 'top-field',
					'hidden' => !$this->config->is_map_enabled()
				)
			));

			$map_fieldset->add_field(new GoogleMapsFormFieldMultipleMarkers('map_markers', $this->lang['contact.map.markers'], $this->config->get_map_markers(),
				array(
					'class' => 'full-field',
					'hidden' => !$this->config->is_map_enabled()
				)
			));
		}

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['contact.authorizations.read'], ContactAuthorizationsService::READ_AUTHORIZATIONS),
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
		$this->config->set_title($this->form->get_value('title'));

		if ($this->form->get_value('informations_enabled'))
		{
			$this->config->enable_informations();
			$this->config->set_informations($this->form->get_value('informations'));
			$this->config->set_informations_position($this->form->get_value('informations_position')->get_raw_value());
		}
		else
			$this->config->disable_informations();

		if ($this->form->get_value('tracking_number_enabled'))
		{
			$this->config->enable_tracking_number();
			if ($this->form->get_value('date_in_tracking_number_enabled'))
				$this->config->enable_date_in_tracking_number();
			else
				$this->config->disable_date_in_tracking_number();
		}
		else
			$this->config->disable_tracking_number();

		if ($this->form->get_value('sender_acknowledgment_enabled'))
			$this->config->enable_sender_acknowledgment();
		else
			$this->config->disable_sender_acknowledgment();

		if ($this->config->is_googlemaps_available())
		{
			if ($this->form->get_value('map_enabled'))
			{
				$this->config->enable_map();
				$this->config->set_map_position($this->form->get_value('map_position')->get_raw_value());
				$this->config->set_map_markers($this->form->get_value('map_markers'));
			}
			else
				$this->config->disable_map();
		}

		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());

		ContactConfig::save();

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}
}
?>
