<?php
/*##################################################
 *                       AdminContactConfigController.class.php
 *                            -------------------
 *   begin                : March 1, 2013
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
			$this->form->get_field_by_id('informations_position')->set_hidden(!$this->config->are_informations_enabled());
			$this->form->get_field_by_id('informations')->set_hidden(!$this->config->are_informations_enabled());
			$this->form->get_field_by_id('date_in_tracking_number_enabled')->set_hidden(!$this->config->is_tracking_number_enabled());
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
		
		$fieldset = new FormFieldsetHTML('configuration', LangLoader::get_message('configuration', 'admin-common'));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['admin.config.title'], $this->config->get_title(),
			array('maxlength' => 255, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldCheckbox('informations_enabled', $this->lang['admin.config.informations_enabled'], $this->config->are_informations_enabled(),
			array('description' => $this->lang['admin.config.informations.explain'], 'events' => array('click' => '
				if (HTMLForms.getField("informations_enabled").getValue()) {
					HTMLForms.getField("informations_position").enable();
					HTMLForms.getField("informations").enable();
				} else {
					HTMLForms.getField("informations_position").disable();
					HTMLForms.getField("informations").disable();
				}'))
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
		
		$fieldset->add_field(new FormFieldCheckbox('tracking_number_enabled', $this->lang['admin.config.tracking_number_enabled'], $this->config->is_tracking_number_enabled(),
			array('events' => array('click' => '
				if (HTMLForms.getField("tracking_number_enabled").getValue()) {
					HTMLForms.getField("date_in_tracking_number_enabled").enable();
				} else {
					HTMLForms.getField("date_in_tracking_number_enabled").disable();
				}'))
			));
		
		$fieldset->add_field(new FormFieldCheckbox('date_in_tracking_number_enabled', $this->lang['admin.config.date_in_date_in_tracking_number_enabled'], $this->config->is_date_in_tracking_number_enabled(),
			array('description' => $this->lang['admin.config.date_in_date_in_tracking_number_enabled.explain'], 'hidden' => !$this->config->is_tracking_number_enabled())
		));
		
		$fieldset->add_field(new FormFieldCheckbox('sender_acknowledgment_enabled', $this->lang['admin.config.sender_acknowledgment_enabled'], $this->config->is_sender_acknowledgment_enabled()));
		
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
		
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		
		ContactConfig::save();
	}
}
?>
