<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 4.0 - 2013 08 04
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminContactFieldFormController extends DefaultAdminModuleController
{
	private $field;
	private $id;
	private $is_new_field;

	protected function get_template_to_use()
	{
		return new FileTemplate('contact/AdminContactFieldFormController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);

		$this->build_form($request);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			if ($this->is_new_field)
				AppContext::get_response()->redirect(ContactUrlBuilder::manage_fields(), StringVars::replace_vars($this->lang['contact.message.success.add'], array('name' => $this->get_field()->get_name())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : ContactUrlBuilder::manage_fields()), StringVars::replace_vars($this->lang['contact.message.success.edit'], array('name' => $this->get_field()->get_name())));
		}

		$this->view->put('CONTENT', $this->form->display());

		if (!$this->get_field()->is_readonly())
			$this->view->put('JS_EVENT_SELECT_TYPE', $this->get_events_select_type());

		return new AdminContactDisplayResponse($this->view, !empty($this->id) ? $this->lang['form.field.edit'] : $this->lang['form.field.add']);
	}

	private function init(HTTPRequestCustom $request)
	{
		$this->id = $request->get_getint('id', 0);
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$field = $this->get_field();

		$regex_type = !$this->is_new_field ? (is_numeric($field->get_regex()) ? $field->get_regex() : 6) : 0;
		$regex = is_string($field->get_regex()) ? $field->get_regex() : '';

		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('field', !empty($this->id) ? $this->lang['form.field.edit'] : $this->lang['form.field.add']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('name', $this->lang['form.name'], $field->get_name(),
			array('class' => 'top-field', 'required' => true),
			array(new ContactConstraintFieldExist($this->id))
		));

		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('description', $this->lang['form.description'], $field->get_description(),
			array('class' => 'top-field', 'rows' => 4, 'cols' => 47)
		));

		$fieldset->add_field(new FormFieldCheckbox('field_required', $this->lang['form.required.field'], (int)$field->is_required(),
			array(
				'class' => 'top-field custom-checkbox',
				'disabled' => $field->is_readonly()
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('display', $this->lang['form.display'], (int)$field->is_displayed(),
			array(
				'class' => 'top-field custom-checkbox',
				'disabled' => $field->is_readonly()
			)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('field_type', $this->lang['form.field.type'], $field->get_field_type(),
			$this->get_array_select_type($field->get_field_name()),
			array('class' => 'top-field', 'disabled' => $field->is_readonly(), 'events' => array('change' => $this->get_events_select_type()))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('regex_type', $this->lang['form.regex'], $regex_type,
			$this->get_array_select_regex(),
			array(
				'disabled' => $field->is_readonly(),
				'description' => $this->lang['form.regex.clue'],
				'events' => array('change' => '
					if (HTMLForms.getField("regex_type").getValue() == 6) {
						HTMLForms.getField("regex").enable();
						jQuery("#' . __CLASS__ . '_regex").focus();
					} else {
						HTMLForms.getField("regex").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('regex', $this->lang['form.personnal.regex'], $regex,
			array('class' => 'top-field', 'hidden' => $field->is_readonly())
		));

		if ($field->get_field_name() == 'f_recipients')
		{
			$fieldset->add_field(new ContactFormFieldRecipientsPossibleValues('possible_values', $this->lang['form.possible.values'], $field->get_possible_values(),
				array(
					'class' => 'top-field half-field',
					'description' => $this->lang['contact.possible.values.email.clue']
				)
			));
		}
		else if ($field->get_field_name() == 'f_subject')
		{
			$fieldset->add_field(new ContactFormFieldObjectPossibleValues('possible_values', $this->lang['form.possible.values'], $field->get_possible_values(),
				array(
					'class' => 'top-field half-field',
					'description' => $this->lang['contact.possible.values.recipient.clue']
				)
			));
		}
		else
		{
			$fieldset->add_field(new FormFieldPossibleValues('possible_values', $this->lang['form.possible.values'], $field->get_possible_values(),
				array('class' => 'half-field', 'hidden' => $field->is_readonly())
			));
		}

		$fieldset->add_field(new FormFieldTextEditor('default_value_small', $this->lang['form.default.value'], $field->get_default_value(),
			array('class' => 'top-field', 'disabled' => $field->is_readonly()
		)));

		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('default_value_medium', $this->lang['form.default.value'], $field->get_default_value(),
			array('rows' => 4, 'cols' => 47, 'hidden' => $field->is_readonly())
		));

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['contact.authorizations.display.field'], ContactField::DISPLAY_FIELD_AUTHORIZATION)
		));
		$auth_settings->build_from_auth_array($field->get_authorization());
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings, array('hidden' => $field->is_readonly()));
		$fieldset->add_field($auth_setter);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_field()
	{
		if ($this->field === null)
		{
			$fields = $this->config->get_fields();

			$this->field = new ContactField();

			if (!empty($this->id) && isset($fields[$this->id]))
			{
				$properties = $fields[$this->id];
				$this->field->set_properties($properties);
			}
			else
				$this->is_new_field = true;
		}
		return $this->field;
	}

	private function save()
	{
		$field = $this->get_field();
		$fields = $this->config->get_fields();

		if ($field->is_deletable())
			$field->set_field_name(ContactField::rewrite_field_name($this->form->get_value('name')));

		$field->set_name($this->form->get_value('name'));
		$field->set_description($this->form->get_value('description'));

		if (!$this->form->field_is_disabled('field_type'))
			$field->set_field_type($this->form->get_value('field_type')->get_raw_value());

		if (!$field->is_readonly() && !$this->form->field_is_disabled('regex_type'))
		{
			$regex = $regex_type = $this->form->get_value('regex_type')->get_raw_value();

			if (!$this->form->field_is_disabled('regex'))
				$regex = $regex_type != 6 ? $regex_type : $this->form->get_value('regex');

			$field->set_regex($regex);
		}

		if (!$this->form->field_is_disabled('field_required'))
		{
			if ((bool)$this->form->get_value('field_required'))
				$field->required();
			else
				$field->not_required();
		}

		if (!$this->form->field_is_disabled('possible_values'))
		{
			$field->set_possible_values($this->form->get_value('possible_values'));
		}

		if (!$this->form->field_is_disabled('default_value_small'))
			$field->set_default_value($this->form->get_value('default_value_small'));

		if (!$this->form->field_is_disabled('default_value_medium'))
			$field->set_default_value($this->form->get_value('default_value_medium'));

		if (!$this->form->field_is_disabled('display'))
		{
			if ((bool)$this->form->get_value('display'))
				$field->displayed();
			else
				$field->not_displayed();
		}

		if (!$this->form->field_is_disabled('authorizations'))
			$field->set_authorization($this->form->get_value('authorizations', $field->get_authorization())->build_auth_array());

		$fields[!empty($this->id) ? $this->id : count($this->config->get_fields()) + 1] = $field->get_properties();

		$this->config->set_fields($fields);

		ContactConfig::save();
	}

	private function get_array_select_type($field_name)
	{
		$types = array();

		foreach ($this->get_fields_class_name($field_name) as $field_type)
		{
			$types[] = new FormFieldSelectChoiceOption($field_type->get_name(), get_class($field_type));
		}
		return $types;
	}

	private function get_array_select_regex()
	{
		return array(
			new FormFieldSelectChoiceOption('--', 0),
			new FormFieldSelectChoiceOption($this->lang['form.figures'], 1),
			new FormFieldSelectChoiceOption($this->lang['form.letters'], 2),
			new FormFieldSelectChoiceOption($this->lang['form.figures.letters'], 3),
			new FormFieldSelectChoiceOption($this->lang['form.word'], 7),
			new FormFieldSelectChoiceOption($this->lang['form.email'], 4),
			new FormFieldSelectChoiceOption($this->lang['form.website'], 5),
			new FormFieldSelectChoiceOption($this->lang['form.phone.number'], 8),
			new FormFieldSelectChoiceOption($this->lang['form.personnal.regex'], 6),
		);
	}

	private function get_events_select_type()
	{
		$event = '';
		$disable_fields = $this->get_disable_fields();
		foreach ($disable_fields as $name_field_disable => $field_type)
		{
			if (!empty($field_type))
			{
				$one_field = array_shift($field_type);
				$event .= 'if (HTMLForms.getField("field_type").getValue() == "'. $one_field .'"';
				foreach ($field_type as $name)
				{
					$event .= ' || HTMLForms.getField("field_type").getValue() == "'. $name .'"';
				}
				$event .= ') {
					HTMLForms.getField("' .$name_field_disable. '").disable();';
					if ($name_field_disable == 'regex')
					{
						$event .= 'HTMLForms.getField("regex_type").disable();';
					}
					$event .= '} else { HTMLForms.getField("' .$name_field_disable. '").enable();';
					if ($name_field_disable == 'regex')
					{
						$event .= 'if (HTMLForms.getField("regex_type").getValue() != 6)
							HTMLForms.getField("regex").disable();
						HTMLForms.getField("regex_type").enable();';
					}
					$event .= '}';
			}
		}
		return $event;
	}

	private function get_disable_fields()
	{
		$disable_field = array(
			'name' => array(),
			'description' => array(),
			'regex' => array(),
			'possible_values' => array(),
			'default_value_small' => array(),
			'default_value_medium' => array(),
		);

		foreach ($this->get_fields_class_name() as $field_type)
		{
			$disable_fields_extended_field = $field_type->get_disable_fields_configuration();

			foreach ($disable_fields_extended_field as $name_disable_field)
			{
				if (array_key_exists($name_disable_field, $disable_field))
				{
					$disable_field[$name_disable_field][] = get_class($field_type);
				}
			}
		}
		return $disable_field;
	}

	private function get_fields_class_name($field_name = '')
	{
		if ($field_name == 'f_recipients')
		{
			return array(
				new ContactSimpleSelectField(),
				new ContactMultipleSelectField(),
				new ContactSimpleChoiceField(),
				new ContactMultipleChoiceField(),
			);
		}
		else if ($field_name == 'f_subject')
		{
			return array(
				new ContactShortTextField(),
				new ContactSimpleSelectField(),
				new ContactSimpleChoiceField(),
			);
		}
		else
		{
			return array(
				new ContactShortTextField(),
				new ContactHalfLongTextField(),
				new ContactLongTextField(),
				new ContactSimpleSelectField(),
				new ContactMultipleSelectField(),
				new ContactSimpleChoiceField(),
				new ContactMultipleChoiceField(),
				new ContactDateField()
			);
		}
	}
}
?>
