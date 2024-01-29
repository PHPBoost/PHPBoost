<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 01 29
 * @since       PHPBoost 3.0 - 2010 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminExtendedFieldMemberEditController extends DefaultAdminController
{
	private $extended_field;

	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id');

		$extended_field = new ExtendedField();
		$extended_field->set_id($id);
		$exist_field = ExtendedFieldsDatabaseService::check_field_exist_by_id($extended_field);
		if ($exist_field)
		{
			$this->extended_field = ExtendedFieldsCache::load()->get_extended_field($id);
			$this->build_form($request);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		$this->view = new StringTemplate('
			# INCLUDE MESSAGE_HELPER #
			# INCLUDE CONTENT #
			<script>
				jQuery(document).ready(function() {
				'.$this->get_events_select_type().'});
			</script>
		');

		$this->view->put_all(array(
			'FIELD_TYPE' => $this->extended_field['field_type']
		));

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$extended_field = $this->save($id);
			$error = ExtendedFieldsService::get_error();
			if (!empty($error))
			{
				$this->view->put('MESSAGE_HELPER', MessageHelper::display($error, MessageHelper::ERROR, 5));
			}
			else
			{
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : AdminExtendedFieldsUrlBuilder::fields_list()), StringVars::replace_vars($this->lang['form.success.edit'], array('name' => $extended_field->get_name())));
			}
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminExtendedFieldsDisplayResponse($this->view, $this->lang['user.extended.field.edit']);
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$regex_type = is_numeric($this->extended_field['regex']) ? $this->extended_field['regex'] : 6;
		$regex = is_string($this->extended_field['regex']) ? $this->extended_field['regex'] : '';

		$fieldset = new FormFieldsetHTML('edit_fields', $this->lang['user.extended.field.edit']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('name', $this->lang['form.name'], $this->extended_field['name'],
			array('class' => 'top-field', 'required' => true)
		));

		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('description', $this->lang['form.description'], $this->extended_field['description']));

		$fieldset->add_field(new FormFieldCheckbox('display', $this->lang['form.display'], (int)$this->extended_field['display'],
			array('class' => 'top-field custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('field_required', $this->lang['form.required.field'], (int)$this->extended_field['required'],
			array(
				'class' => 'top-field custom-checkbox',
				'description' => $this->lang['form.required.field.clue']
			)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('field_type', $this->lang['form.field.type'], $this->extended_field['field_type'], $this->get_array_select_type(),
			array(
				'class' => 'top-field',
				'disabled' => $this->is_type_select_disabled(),
				'events' => array('change' => $this->get_events_select_type())
			)
		));

		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('default_value', $this->lang['form.default.value'], $this->extended_field['default_value'],
			array('rows' => 4)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('regex_type', $this->lang['form.regex'], $regex_type,
			array(
				new FormFieldSelectChoiceOption('--', '0'),
				new FormFieldSelectChoiceOption($this->lang['form.figures'], '1'),
				new FormFieldSelectChoiceOption($this->lang['form.letters'], '2'),
				new FormFieldSelectChoiceOption($this->lang['form.figures.letters'], '3'),
				new FormFieldSelectChoiceOption($this->lang['form.word'], '7'),
				new FormFieldSelectChoiceOption($this->lang['form.email'], '4'),
				new FormFieldSelectChoiceOption($this->lang['form.website'], '5'),
				new FormFieldSelectChoiceOption($this->lang['form.phone.number'], '8'),
				new FormFieldSelectChoiceOption($this->lang['form.personnal.regex'], '6'),
			),
			array(
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
			array('class' => 'top-field')
		));

		$fieldset->add_field(new FormFieldPossibleValues('possible_values', $this->lang['form.possible.values'], $this->extended_field['possible_values'],
            array(
                'class' => 'full-field',
                'description' => $this->lang['form.select.default.value']
            )
        ));

		$auth = $this->extended_field['auth'];

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['form.authorizations.read'], ExtendedField::READ_PROFILE_AUTHORIZATION),
			new ActionAuthorization($this->lang['form.authorizations.moderation'], ExtendedField::READ_EDIT_AND_ADD_AUTHORIZATION)
		));
		$auth_settings->build_from_auth_array($auth);
		$fieldset->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save($id)
	{
		$extended_field = new ExtendedField();
		$extended_field->set_id($id);
		$extended_field = ExtendedFieldsService::data_field($extended_field);
		$freeze = $extended_field->get_is_freeze();
		if (!$freeze)
		{
			$extended_field->set_field_name(ExtendedField::rewrite_field_name($this->form->get_value('name', $extended_field->get_field_name())));
			$extended_field->set_field_type($this->form->get_value('field_type', $extended_field->get_field_type())->get_raw_value());
		}
		else
		{
			$extended_field->set_field_name(TextHelper::htmlspecialchars($extended_field->get_field_name()));
			$extended_field->set_field_type($extended_field->get_field_type());
		}

		$extended_field->set_name(TextHelper::htmlspecialchars($this->form->get_value('name')));
		$extended_field->set_position(PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, 'MAX(position) + 1', ''));
		$extended_field->set_description(TextHelper::htmlspecialchars($this->form->get_value('description', $extended_field->get_description())));

		if (!$this->form->field_is_disabled('possible_values'))
		{
			$extended_field->set_possible_values($this->form->get_value('possible_values'));
		}

		if (!$this->form->field_is_disabled('default_value'))
			$extended_field->set_default_value($this->form->get_value('default_value'));

		$extended_field->set_is_required((bool)$this->form->get_value('field_required'));
		$extended_field->set_display((bool)$this->form->get_value('display'));
		$regex = $regex_type = !$this->form->field_is_disabled('regex_type') ? $this->form->get_value('regex_type')->get_raw_value() : 0;

		if (!$this->form->field_is_disabled('regex'))
		{
			$regex = $regex_type != 6 ? $regex_type : $this->form->get_value('regex');
		}

		$extended_field->set_regex($regex);
		$extended_field->set_authorization($this->form->get_value('authorizations', $extended_field->get_authorization())->build_auth_array());

		ExtendedFieldsService::update($extended_field);

		return $extended_field;
	}

	private function get_array_select_type()
	{
		$select = array();
		$modules = $this->get_extended_fields_class_name();

		foreach ($modules as $module => $files)
		{
			if (count($modules) > 1)
			{
				if ($module == 'kernel')
				{
					$kernel_select = array();
					foreach ($files as $field_type)
					{
						$kernel_select[] = new FormFieldSelectChoiceOption($field_type->get_name(), get_class($field_type));
					}
					$select[] = new FormFieldSelectChoiceGroupOption($this->lang['form.default.field'], $kernel_select);
				}
				else
				{
					$module_select = array();
					foreach ($files as $field_type)
					{
						$module_select[] = new FormFieldSelectChoiceOption($field_type->get_name(), get_class($field_type));
					}

					$module_name = ModulesManager::get_module($module)->get_configuration()->get_name();
					$select[] = new FormFieldSelectChoiceGroupOption($module_name, $module_select);
				}
			}
			else
			{
				foreach ($files as $field_type)
				{
					$select[] = new FormFieldSelectChoiceOption($field_type->get_name(), get_class($field_type));
				}
			}
		}
		return $select;
	}

	private function is_type_select_disabled()
	{
		$disabled = false;
		foreach ($this->get_extended_fields_class_name() as $module => $files)
		{
			foreach ($files as $field_type)
			{
				if (get_class($field_type) == $this->extended_field['field_type'] && $field_type->get_field_used_once())
					$disabled = true;
			}
		}
		return $disabled;
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
			'possible_values' => array(),
			'default_value' => array(),
			'field_required' => array(),
			'regex' => array(),
			'authorizations' => array()
		);

		foreach ($this->get_extended_fields_class_name() as $module => $files)
		{
			foreach ($files as $field_type)
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
		}
		return $disable_field;
	}

	private function get_extended_fields_class_name()
	{
		$providers = AppContext::get_extension_provider_service()->get_providers(ExtendedFieldExtensionPoint::EXTENSION_POINT);

		$extended_fields_class_name = array();
		foreach ($providers as $name_provider => $properties)
		{
			$extended_fields_extension_point = $properties->get_extension_point(ExtendedFieldExtensionPoint::EXTENSION_POINT);
			$extended_fields = $extended_fields_extension_point->get_extended_fields();

			$extended_fields_list = array();
			foreach ($extended_fields as $extended_field)
			{
				if (!$extended_field->get_field_used_once() || get_class($extended_field) == $this->extended_field['field_type'])
					$extended_fields_list[] = $extended_field;
			}

			if (!empty($extended_fields_list))
				$extended_fields_class_name[$name_provider] = $extended_fields_list;
		}
		return $extended_fields_class_name;
	}
}
?>
