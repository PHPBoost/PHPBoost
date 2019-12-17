<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 18
 * @since       PHPBoost 4.0 - 2013 03 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminContactConfigController extends AdminModuleController
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
	 * @var ContactConfig
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
			$this->form->get_field_by_id('informations_position')->set_hidden(!$this->config->are_informations_enabled());
			$this->form->get_field_by_id('informations')->set_hidden(!$this->config->are_informations_enabled());
			$this->form->get_field_by_id('date_in_tracking_number_enabled')->set_hidden(!$this->config->is_tracking_number_enabled());
			if ($this->config->is_googlemaps_available())
			{
				$this->form->get_field_by_id('map_position')->set_hidden(!$this->config->is_map_enabled());
				$this->form->get_field_by_id('map_markers')->set_hidden(!$this->config->is_map_enabled());
			}
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminContactDisplayResponse($tpl, $this->lang['module_config_title']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'contact');
		$this->config = ContactConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('configuration', LangLoader::get_message('configuration', 'admin-common'));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['admin.config.title'], $this->config->get_title(),
			array('maxlength' => 255, 'required' => true)
		));

		$fieldset->add_field(new FormFieldCheckbox('sender_acknowledgment_enabled', $this->lang['admin.config.sender_acknowledgment_enabled'], $this->config->is_sender_acknowledgment_enabled(),
			array('class' => 'custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('tracking_number_enabled', $this->lang['admin.config.tracking_number_enabled'], $this->config->is_tracking_number_enabled(),
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

		$fieldset->add_field(new FormFieldCheckbox('date_in_tracking_number_enabled', $this->lang['admin.config.date_in_date_in_tracking_number_enabled'], $this->config->is_date_in_tracking_number_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['admin.config.date_in_date_in_tracking_number_enabled.explain'],
				'hidden' => !$this->config->is_tracking_number_enabled()
			)
		));

		$fieldset->add_field(new FormFieldFree('1_separator', '', ''));

		$fieldset->add_field(new FormFieldCheckbox('informations_enabled', $this->lang['admin.config.informations_enabled'], $this->config->are_informations_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['admin.config.informations.explain'],
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

		$fieldset->add_field(new FormFieldSimpleSelectChoice('informations_position', $this->lang['admin.config.informations_position'], $this->config->get_informations_position(),
			array(
				new FormFieldSelectChoiceOption($this->lang['admin.config.informations.position_left'], ContactConfig::LEFT),
				new FormFieldSelectChoiceOption($this->lang['admin.config.informations.position_top'], ContactConfig::TOP),
				new FormFieldSelectChoiceOption($this->lang['admin.config.informations.position_right'], ContactConfig::RIGHT),
				new FormFieldSelectChoiceOption($this->lang['admin.config.informations.position_bottom'], ContactConfig::BOTTOM),
				),
			array('hidden' => !$this->config->are_informations_enabled())
		));

		$fieldset->add_field(new FormFieldRichTextEditor('informations', $this->lang['admin.config.informations_content'],
			FormatingHelper::unparse($this->config->get_informations()),
			array('rows' => 8, 'cols' => 47, 'hidden' => !$this->config->are_informations_enabled())
		));

		if ($this->config->is_googlemaps_available())
		{
			$map_fieldset = new FormFieldsetHTML('map', $this->lang['admin.config.map'], array('disabled' => !$this->config->is_googlemaps_available()));
			$form->add_fieldset($map_fieldset);

			$map_fieldset->add_field(new FormFieldCheckbox('map_enabled', $this->lang['admin.config.map_enabled'], $this->config->is_map_enabled(),
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

			$map_fieldset->add_field(new FormFieldSimpleSelectChoice('map_position', $this->lang['admin.config.map_position'], $this->config->get_map_position(),
				array(
					new FormFieldSelectChoiceOption($this->lang['admin.config.map.position_top'], ContactConfig::MAP_TOP),
					new FormFieldSelectChoiceOption($this->lang['admin.config.map.position_bottom'], ContactConfig::MAP_BOTTOM),
					),
				array('class' => 'top-field', 'hidden' => !$this->config->is_map_enabled())
			));

			$map_fieldset->add_field(new GoogleMapsFormFieldMultipleMarkers('map_markers', $this->lang['admin.config.map.markers'], $this->config->get_map_markers(),
				array(
					'class' => 'full-field',
					'hidden' => !$this->config->is_map_enabled()
				)
			));
		}

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', LangLoader::get_message('authorizations', 'common'));
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['admin.authorizations.read'], ContactAuthorizationsService::READ_AUTHORIZATIONS),
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
	}
}
?>
