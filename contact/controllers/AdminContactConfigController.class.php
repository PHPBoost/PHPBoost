<?php
/*##################################################
 *                       AdminContactConfigController.class.php
 *                            -------------------
 *   begin                : March 1, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class AdminContactConfigController extends AdminController
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

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			if (!$this->form->get_value('title'))
				$tpl->put('MSG', MessageHelper::display($this->lang['contact.message.error_empty_title'], E_USER_ERROR, 5));
			else if (!$this->validate_select_default_value())
				$tpl->put('MSG', MessageHelper::display($this->lang['contact.message.error_default_value'], E_USER_ERROR, 5));
			else
			{
				$this->save();
				$tpl->put('MSG', MessageHelper::display($this->lang['contact.message.success_saving_config'], E_USER_SUCCESS, 5));
			}
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminContactDisplayResponse($tpl, $this->lang['contact_config']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('contact_common', 'contact');
	}

	private function build_form()
	{
		$form = new HTMLForm('contact_admin');
		$config = ContactConfig::load();

		$fieldset = new FormFieldsetHTML('configuration', $this->lang['contact_config']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['contact_config.title'], $config->get_title(), array(
			'class' => 'text', 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldCheckbox('display_subject_field', $this->lang['contact_config.display_subject_field'], $config->is_subject_field_displayed(),
			array('events' => array('click' => '
				if (HTMLForms.getField("display_subject_field").getValue()) {
					HTMLForms.getField("subject_field_mandatory").enable();
					HTMLForms.getField("subject_field_type").enable();
				} else {
					HTMLForms.getField("subject_field_mandatory").disable();
					HTMLForms.getField("subject_field_type").disable();
				}'))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('subject_field_mandatory', $this->lang['contact_config.subject_field_mandatory'], $config->is_subject_field_mandatory(),
			array ('hidden' => !$config->is_subject_field_displayed())
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('subject_field_type', $this->lang['contact_config.subject_field_type'], $config->get_subject_field_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['contact_config.text'], ContactConfig::TEXT_TYPE),
				new FormFieldSelectChoiceOption($this->lang['contact_config.select'], ContactConfig::SELECT_TYPE),
			),
			array('hidden' => !$config->is_subject_field_displayed(), 'events' => array('change' => '
				if (HTMLForms.getField("subject_field_type").getValue() == "' . ContactConfig::TEXT_TYPE . '") { 
					HTMLForms.getField("subject_field_possible_values").disable();
				} else { 
					HTMLForms.getField("subject_field_possible_values").enable();
				}'))
		));
		
		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('subject_field_possible_values', $this->lang['contact_config.possible_values'], $config->get_subject_field_possible_values(), array(
			'class' => 'text', 'width' => 60, 'rows' => 4,'description' => LangLoader::get_message('field.possible-values-explain', 'admin-extended-fields-common'), 'required' => $config->is_subject_field_select(), 'hidden' => !$config->is_subject_field_select())
		));
		
		$fieldset->add_field(new FormFieldTextEditor('subject_field_default_value', $this->lang['contact_config.default_value'], $config->get_subject_field_default_value(), array(
			'class' => 'text')
		));
		
		$fieldset = new FormFieldsetHTML('informations_bloc', $this->lang['contact_config.informations_bloc']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('enable_informations', $this->lang['contact_config.display_informations_bloc'], $config->is_informations_enabled(),
			array('description' => $this->lang['contact_config.informations.explain'], 'events' => array('click' => '
				if (HTMLForms.getField("enable_informations").getValue()) {
					HTMLForms.getField("informations_position").enable();
					HTMLForms.getField("informations").enable();
				} else {
					HTMLForms.getField("informations_position").disable();
					HTMLForms.getField("informations").disable();
				}'))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('informations_position', $this->lang['contact_config.informations_position'], $config->get_informations_position(),
			array(
				new FormFieldSelectChoiceOption($this->lang['left'], ContactConfig::LEFT),
				new FormFieldSelectChoiceOption($this->lang['top'], ContactConfig::TOP),
				new FormFieldSelectChoiceOption($this->lang['right'], ContactConfig::RIGHT),
				new FormFieldSelectChoiceOption($this->lang['bottom'], ContactConfig::BOTTOM),
			),
			array('hidden' => !$config->is_informations_enabled())
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('informations', $this->lang['contact_config.informations_content'], 
			FormatingHelper::unparse($config->get_informations()), 
			array('class' => 'text', 'rows' => 8, 'cols' => 47, 'hidden' => !$config->is_informations_enabled())
		));
		
		$fieldset = new FormFieldsetHTML('anti-spam', $this->lang['contact_config.anti_spam']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('enable_captcha', $this->lang['contact_config.enable_captcha'], $config->is_captcha_enabled(),
			array('events' => array('click' => 'if (HTMLForms.getField("enable_captcha").getValue()) { HTMLForms.getField("captcha_difficulty_level").enable(); } else { HTMLForms.getField("captcha_difficulty_level").disable(); }'))));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('captcha_difficulty_level', $this->lang['contact_config.captcha_difficulty'], $config->get_captcha_difficulty_level(), $this->generate_difficulty_level_options(),
			array('hidden' => !$config->is_captcha_enabled())));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}

	private function generate_difficulty_level_options()
	{
		$options = array();
		for ($i = 0; $i <= 4; $i++)
		{
			$options[] = new FormFieldSelectChoiceOption($i, $i);
		}
		return $options;
	}
	
	private function save()
	{
		$config = ContactConfig::load();
		
		$config->set_title($this->form->get_value('title'));
		
		if ($this->form->get_value('enable_informations'))
		{
			$config->enable_informations();
			$config->set_informations($this->form->get_value('informations'));
			$config->set_informations_position($this->form->get_value('informations_position')->get_raw_value());
		}
		else
			$config->disable_informations();
		
		if ($this->form->get_value('display_subject_field'))
		{
			$config->display_subject_field();
			$config->set_subject_field_type($this->form->get_value('subject_field_type')->get_raw_value());
			
			if ($this->form->get_value('subject_field_type')->get_raw_value() == ContactConfig::SELECT_TYPE)
				$config->set_subject_field_possible_values($this->form->get_value('subject_field_possible_values'));
			
			if ($this->form->get_value('subject_field_mandatory'))
				$config->subject_field_mandatory();
			else
				$config->not_subject_field_mandatory();
		}
		else
			$config->not_display_subject_field();
		
		$config->set_subject_field_default_value($this->form->get_value('subject_field_default_value'));
		
		if ($this->form->get_value('enable_captcha'))
		{
			$config->enable_captcha();
			$config->set_captcha_difficulty_level($this->form->get_value('captcha_difficulty_level')->get_raw_value());
		}
		else
			$config->disable_captcha();
		
		ContactConfig::save();
	}
	
	private function validate_select_default_value()
	{
		if ($this->form->get_value('display_subject_field') && $this->form->get_value('subject_field_type')->get_raw_value() == ContactConfig::SELECT_TYPE && $this->form->get_value('subject_field_default_value'))
		{
			$possible_values = explode('|', $this->form->get_value('subject_field_possible_values'));
			if (!in_array($this->form->get_value('subject_field_default_value'), $possible_values))
				return false;
		}
		return true;
	}
}
?>