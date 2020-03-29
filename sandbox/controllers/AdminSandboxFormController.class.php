<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 01
 * @since       PHPBoost 5.2 - 2019 11 01
*/

class AdminSandboxFormController extends AdminModuleController
{
	private $tpl;
	private $lang;
	private $common_lang;

	/**
	 * @var FormButtonSubmit
	 */
	private $preview_button;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$form = $this->build_form();

		if (ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key())
			$c_gmap = true;
		else
			$c_gmap = false;

		$this->tpl->put_all(array('C_GMAP' => $c_gmap));

		if ($this->submit_button->has_been_submited() || $this->preview_button->has_been_submited())
		{
			if ($form->validate())
			{
				$this->tpl->put_all(array(
					'C_RESULT' => true,
					'TEXT' => $form->get_value('text'),
					'MAIL' => $form->get_value('mail'),
					'WEB' => $form->get_value('siteweb'),
					'AGE' => $form->get_value('age'),
					'MULTI_LINE_TEXT' => $form->get_value('multi_line_text'),
					'RICH_TEXT' => $form->get_value('rich_text'),
					'RADIO' => $form->get_value('radio')->get_label(),
					'CHECKBOX' => var_export($form->get_value('checkbox'), true),
					'SELECT' => $form->get_value('select')->get_label(),
					'HIDDEN' => $form->get_value('hidden'),
					'DATE' => $form->get_value('date')->format(Date::FORMAT_DAY_MONTH_YEAR),
					'DATE_TIME' => $form->get_value('date_time')->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
					'H_T_TEXT_FIELD' => $form->get_value('alone'),
					'C_PREVIEW' => $this->preview_button->has_been_submited()
				));

				$file = $form->get_value('file');
				if ( $file !== null)
				{
					$this->tpl->put_all(array('FILE' => $file->get_name() . ' - ' . $file->get_size() . 'b - ' . $file->get_mime_type()));
				}
			}
		}

		$this->tpl->put('form', $form->display());
		$this->tpl->add_lang($this->lang);


		return new AdminSandboxDisplayResponse($this->tpl, $this->lang['form.title']);
	}

	private function init()
	{
		$this->common_lang = LangLoader::get('common', 'sandbox');
		$this->lang = LangLoader::get('form', 'sandbox');
		$this->tpl = new FileTemplate('sandbox/SandboxFormController.tpl');
		$this->tpl->add_lang($this->common_lang);
		$this->tpl->add_lang($this->lang);
	}

	private function build_form()
	{
		$security_config = SecurityConfig::load();
		$form = new HTMLForm('sandboxForm');

		// FIELDSET
		$fieldset = new FormFieldsetHTML('fieldset_1', $this->lang['form.title']);
		$form->add_fieldset($fieldset);

		$fieldset->set_description($this->lang['form.desc']);

		// Subtitle
		$fieldset->add_field(new FormFieldSubTitle('checkbox_subtitle', $this->lang['form.subtitle'], ''));

		// SINGLE LINE TEXT
		$fieldset->add_field(new FormFieldTextEditor('text', $this->lang['form.input.text'], $this->lang['form.input.text.lorem'],
			array('maxlength' => 25, 'description' => $this->lang['form.input.text.desc'], 'class' => 'form-field-css-class'),
			array(new FormFieldConstraintRegex('`^[a-z0-9_ ]+$`iu'))
		));
		$fieldset->add_field(new FormFieldTextEditor('textdisabled', $this->lang['form.input.text.disabled'], '',
			array('maxlength' => 25, 'description' => $this->lang['form.input.text.disabled.desc'], 'disabled' => true, 'class' => 'form-field-css-class')
		));
		$fieldset->add_field(new FormFieldUrlEditor('siteweb', $this->lang['form.input.url'], $this->lang['form.input.url.placeholder'],
			array('description' => $this->lang['form.input.url.desc'], 'class' => 'form-field-css-class')
		));
		$fieldset->add_field(new FormFieldMailEditor('mail', $this->lang['form.input.email'], $this->lang['form.input.email.placeholder'],
			array('description' => $this->lang['form.input.email.desc'], 'class' => 'form-field-css-class')
		));
		$fieldset->add_field(new FormFieldMailEditor('mail_multiple', $this->lang['form.input.email.multiple'], $this->lang['form.input.email.multiple.placeholder'],
			array('description' => $this->lang['form.input.email.multiple.desc'], 'multiple' => true, 'class' => 'form-field-css-class')
		));
		$fieldset->add_field(new FormFieldTelEditor('tel', $this->lang['form.input.phone'], $this->lang['form.input.phone.placeholder'],
			array('description' => $this->lang['form.input.phone.desc'], 'class' => 'form-field-css-class')
		));
		$fieldset->add_field(new FormFieldTextEditor('text2', $this->lang['form.input.text.required'], $this->lang['form.input.text.lorem'],
			array('maxlength' => 25, 'description' => $this->lang['form.input.text.required.filled'], 'required' => true, 'class' => 'form-field-css-class')
		));
		$fieldset->add_field(new FormFieldTextEditor('text3', $this->lang['form.input.text.required'], '',
			array('maxlength' => 25, 'description' => $this->lang['form.input.text.required.empty'], 'required' => true, 'class' => 'form-field-css-class')
		));
		$fieldset->add_field(new FormFieldNumberEditor('age', $this->lang['form.input.number'], $this->lang['form.input.number.placeholder'],
			array('min' => 10, 'max' => 100, 'description' => $this->lang['form.input.number.desc'], 'class' => 'form-field-css-class'),
			array(new FormFieldConstraintIntegerRange(10, 100))
		));
		$fieldset->add_field(new FormFieldDecimalNumberEditor('decimal', $this->lang['form.input.number.decimal'], $this->lang['form.input.number.decimal.placeholder'],
			array('min' => 0, 'step' => 0.1, 'description' => $this->lang['form.input.number.decimal.desc'], 'class' => 'form-field-css-class')
		));

		// RANGE
		$fieldset->add_field($password = new FormFieldRangeEditor('range', $this->lang['form.input.length'], $this->lang['form.input.length.placeholder'],
			array('min' => 1, 'max' => 10, 'description' => $this->lang['form.input.length.desc'], 'class' => 'form-field-css-class')
		));

		// PASSWORD
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['form.input.password'], $this->lang['form.input.password.placeholder'],
			array('description' => $security_config->get_internal_password_min_length() . $this->lang['form.input.password.desc'], 'class' => 'form-field-css-class'),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()))
		));
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['form.input.password.confirm'], $this->lang['form.input.password.placeholder'],
			array('description' => $security_config->get_internal_password_min_length() . $this->lang['form.input.password.desc'], 'class' => 'form-field-css-class'),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()))
		));

		// SHORT MULTI LINE TEXT
		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('short_multi_line_text', $this->lang['form.input.multiline.medium'], $this->lang['form.input.multiline.lorem'],
			array('rows' => 3, 'required' => true, 'class' => 'form-field-css-class')
		));

		// MULTI LINE TEXT
		$fieldset->add_field(new FormFieldMultiLineTextEditor('multi_line_text', $this->lang['form.input.multiline'], $this->lang['form.input.multiline.lorem'],
			array('rows' => 6, 'cols' => 47, 'description' => $this->lang['form.input.multiline.desc'], 'required' => true, 'class' => 'form-field-css-class')
		));

		// RICH TEXT
		$fieldset->add_field(new FormFieldRichTextEditor('rich_text', $this->lang['form.input.rich.text'], $this->lang['form.input.rich.text.placeholder'],
			array('required' => true, 'class' => 'form-field-css-class')
		));

		// Checkbox
		$fieldset->add_field(new FormFieldCheckbox('checkbox', $this->lang['form.input.checkbox'], FormFieldCheckbox::CHECKED,
			array('class' => 'top-field form-field-css-class')
		));

		// Custom Checkbox
		$fieldset->add_field(new FormFieldCheckbox('custom_checkbox', $this->lang['form.input.checkbox'], FormFieldCheckbox::CHECKED,
			array('description' => 'custom', 'class' => 'top-field custom-checkbox form-field-css-class')
		));

		// Mini Checkbox
		$fieldset->add_field(new FormFieldCheckbox('mini_checkbox', $this->lang['form.input.checkbox'], FormFieldCheckbox::CHECKED,
			array('description' => 'mini', 'class' => 'top-field mini-checkbox form-field-css-class')
		));

		// Multiple checkboxes
		$fieldset->add_field(new FormFieldMultipleCheckbox('multiple_checkbox', $this->lang['form.input.multiple.checkbox'],
			array('1'),
			array(
				new FormFieldMultipleCheckboxOption('1', $this->lang['form.input.choice.1']),
				new FormFieldMultipleCheckboxOption('2', $this->lang['form.input.choice.2'])
			),
			array('description' => 'mini', 'required' => true, 'class' => 'mini-checkbox form-field-css-class')
		));

		// Multiple inline checkboxes
		$fieldset->add_field(new FormFieldMultipleCheckbox('inline_multiple_checkbox', $this->lang['form.input.multiple.checkbox'],
			array('1'),
			array(
				new FormFieldMultipleCheckboxOption('1', $this->lang['form.input.choice.1']),
				new FormFieldMultipleCheckboxOption('2', $this->lang['form.input.choice.2'])
			),
			array('description' => 'inline - mini', 'required' => true, 'class' => 'inline-checkbox mini-checkbox form-field-css-class')
		));

		// Separator
		$fieldset->add_field(new FormFieldFree('01_separator', '', ''));

		// Inline radio inputs
		$default_option = new FormFieldRadioChoiceOption($this->lang['form.input.choice.1'], '1');
		$fieldset->add_field(new FormFieldRadioChoice('inline_radio', $this->lang['form.input.radio'], '',
			array(
				$default_option,
				new FormFieldRadioChoiceOption($this->lang['form.input.choice.2'], '2')
			),
			array('description' => 'inline', 'required' => true, 'class' => 'top-field form-field-css-class inline-radio')
		));

		// Inline custom radio inputs
		$default_option = new FormFieldRadioChoiceOption($this->lang['form.input.choice.1'], '1');
		$fieldset->add_field(new FormFieldRadioChoice('inline_custom_radio', $this->lang['form.input.radio'], '',
			array(
				$default_option,
				new FormFieldRadioChoiceOption($this->lang['form.input.choice.2'], '2')
			),
			array('description' => 'inline - custom', 'required' => true, 'class' => 'top-field form-field-css-class inline-radio custom-radio')
		));

		// Custom radio inputs
		$default_option = new FormFieldRadioChoiceOption($this->lang['form.input.choice.1'], '1');
		$fieldset->add_field(new FormFieldRadioChoice('radio', $this->lang['form.input.radio'], '',
			array(
				$default_option,
				new FormFieldRadioChoiceOption($this->lang['form.input.choice.2'], '2')
			),
			array('description' => 'custom', 'required' => true, 'class' => 'form-field-css-class custom-radio')
		));

		// Separator
		$fieldset->add_field(new FormFieldFree('02_separator', '', ''));

		// SELECT
		$fieldset->add_field(new FormFieldSimpleSelectChoice('select', $this->lang['form.input.select'], '',
			array(
				new FormFieldSelectChoiceOption('', ''),
				new FormFieldSelectChoiceOption($this->lang['form.input.choice.1'], '1'),
				new FormFieldSelectChoiceOption($this->lang['form.input.choice.2'], '2'),
				new FormFieldSelectChoiceOption($this->lang['form.input.choice.3'], '3'),
				new FormFieldSelectChoiceGroupOption($this->lang['form.input.choice.group.1'],
					array(
						new FormFieldSelectChoiceOption($this->lang['form.input.choice.4'], '4'),
						new FormFieldSelectChoiceOption($this->lang['form.input.choice.5'], '5'),
					)
				),
				new FormFieldSelectChoiceGroupOption($this->lang['form.input.choice.group.2'],
					array(
						new FormFieldSelectChoiceOption($this->lang['form.input.choice.6'], '6'),
						new FormFieldSelectChoiceOption($this->lang['form.input.choice.7'], '7'),
					)
				)
			),
			array('required' => true, 'class' => 'top-field form-field-css-class')
		));

		// SELECT MULTIPLE
		$fieldset->add_field(new FormFieldMultipleSelectChoice('multiple_select', $this->lang['form.input.multiple.select'],
			array('1', '2'),
			array(
				new FormFieldSelectChoiceOption($this->lang['form.input.choice.1'], '1'),
				new FormFieldSelectChoiceOption($this->lang['form.input.choice.2'], '2'),
				new FormFieldSelectChoiceOption($this->lang['form.input.choice.3'], '3')
			),
			array('required' => true, 'class' => 'form-field-css-class')
		));

		$fieldset->add_field(new FormFieldTimezone('timezone', $this->lang['form.input.timezone'], 'UTC+0',
			array('class' => 'top-field form-field-css-class')
		));

		$fieldset->add_field(new FormFieldAjaxSearchUserAutoComplete('user_completition', $this->lang['form.input.user.completion'], '',
			array('class' => 'top-field form-field-css-class')
		));

		// Separator
		$fieldset->add_field(new FormFieldFree('03_separator', '', ''));

		// Buttons
		// Subtitle
		$fieldset->add_field(new FormFieldSubTitle('button_subtitle', $this->lang['form.button'].'s', ''));

		$fieldset->add_element(new FormButtonButton($this->lang['form.button'], '', '', 'button'));
		$fieldset->add_element(new FormButtonButton($this->lang['form.button.small'], '', '', 'small'));
		$fieldset->add_element(new FormButtonButton($this->lang['form.button.basic'], '', '', 'alt-button'));
		$fieldset->add_element(new FormButtonButton($this->lang['form.button.basic.alt'], '', '', 'alt-button alt'));
		$fieldset->add_element(new FormButtonButton($this->lang['form.send.button'], '', '', 'submit'));
		$fieldset->add_element(new FormButtonButton($this->lang['form.send.button.alt'], '', '', 'alt-submit'));

		$fieldset2 = new FormFieldsetHTML('fieldset2', $this->lang['form.title.2']);
		$form->add_fieldset($fieldset2);

		// CAPTCHA
		$fieldset2->add_field(new FormFieldCaptcha('Captcha'));

		// HIDDEN
		$fieldset2->add_field(new FormFieldHidden('hidden', $this->lang['form.input.hidden']));

		// FREE FIELD
		$fieldset2->add_field(new FormFieldFree('free', $this->lang['form.free.html'], $this->lang['form.input.text.lorem'],
			array('class' => 'form-field-css-class')
		));

		// DATE
		$fieldset2->add_field(new FormFieldDate('date', $this->lang['form.date'], null,
			array('required' => true, 'class' => 'form-field-css-class')
		));

		// DATE TIME
		$fieldset2->add_field(new FormFieldDateTime('date_time', $this->lang['form.date.hm'], null,
			array('required' => true, 'class' => 'half-field form-field-css-class')
		));

		// COLOR PICKER
		$fieldset2->add_field(new FormFieldColorPicker('color', $this->lang['form.color'], '#366393',
			array('class' => 'form-field-css-class')
		));

		// SEARCH
		$fieldset2->add_field(new FormFieldSearch('search', $this->lang['form.search'], '',
			array('class' => 'form-field-css-class')
		));

		// Separator
		$fieldset2->add_field(new FormFieldFree('04_separator', '', ''));

		// FILE PICKER
		$fieldset2->add_field(new FormFieldFilePicker('file', $this->lang['form.file.picker'],
			array('class' => 'half-field form-field-css-class')
		));

		// MULTIPLE FILE PICKER
		$fieldset2->add_field(new FormFieldFilePicker('multiple_files', $this->lang['form.multiple.file.picker'],
			array('class' => 'half-field form-field-css-class', 'multiple' => true)
		));

		// UPLOAD FILE
		$fieldset2->add_field(new FormFieldUploadFile('upload_file', $this->lang['form.file.upload'], '',
			array('required' => true, 'class' => 'half-field top-field form-field-css-class')
		));

		// Separator
		$fieldset2->add_field(new FormFieldFree('05_separator', '', ''));

		// List actionLinks
		// Subtitle
		$fieldset2->add_field(new FormFieldSubTitle('links_subtitle', $this->lang['form.action.link.list'], ''));

		$fieldset2->add_field(new FormFieldActionLinkList('actionlink_list',
			array(
				new FormFieldActionLinkElement($this->lang['form.action.link.1'], '#', 'fa-share'),
				new FormFieldActionLinkElement($this->lang['form.action.link.2'], '#', '', PATH_TO_ROOT.'/sandbox/sandbox_mini.png'),
				new FormFieldActionLinkElement($this->lang['form.action.link.3'], '#', ''),
				new FormFieldActionLinkElement($this->lang['form.action.link.4'], '#', '')
			),
			array('class' => 'form-field-css-class')
		));

		// GOOGLE MAPS
		if (ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key())
		{
			$fieldset_maps = new FormFieldsetHTML('fieldset_maps', $this->lang['form.googlemap']);
			$form->add_fieldset($fieldset_maps);

			// SIMPLE ADDRESS
			$fieldset_maps->add_field(new GoogleMapsFormFieldSimpleAddress('simple_address', $this->lang['form.googlemap.simple_address'], '',
				array('class' => 'top-field half-field form-field-css-class')
			));

			// MAP ADDRESS
			$fieldset_maps->add_field(new GoogleMapsFormFieldMapAddress('map_address', $this->lang['form.googlemap.map_address'], '',
				array('class' => 'top-field half-field form-field-css-class', 'include_api' => false)
			));

			// SIMPLE MARKER
			$fieldset_maps->add_field(new GoogleMapsFormFieldSimpleMarker('simple_marker', $this->lang['form.googlemap.simple_marker'], '',
				array('class' => 'top-field half-field form-field-css-class', 'include_api' => false)
			));

			// MULTIPLE MARKERS
			$fieldset_maps->add_field(new GoogleMapsFormFieldMultipleMarkers('multiple_markers', $this->lang['form.googlemap.multiple_markers'], '',
				array('class' => 'top-field half-field form-field-css-class', 'include_api' => false)
			));
		}

		// AUTH
		$fieldset3 = new FormFieldsetHTML('fieldset3', $this->lang['form.authorization']);
		$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($this->lang['form.authorization.1'], 1, $this->lang['form.authorization.1.desc']), new ActionAuthorization($this->lang['form.authorization.2'], 2)));
		$auth_settings->build_from_auth_array(array('r1' => 3, 'r0' => 2, 'm1' => 1, '1' => 2));
		$auth_setter = new FormFieldAuthorizationsSetter('auth', $auth_settings);
		$fieldset3->add_field($auth_setter);
		$form->add_fieldset($fieldset3);

		// VERTICAL FIELDSET
		$vertical_fieldset = new FormFieldsetVertical('fieldset4');
		$vertical_fieldset->set_description($this->lang['form.vertical.desc']);
		$form->add_fieldset($vertical_fieldset);
		$vertical_fieldset->add_field(new FormFieldTextEditor('alone', $this->lang['form.input.text'], $this->lang['form.input.text.lorem'], array('class' => 'form-field-css-class')));
		$vertical_fieldset->add_field(new FormFieldCheckbox('cbhor', $this->lang['form.input.checkbox'], FormFieldCheckbox::UNCHECKED, array('class' => 'form-field-css-class')));

		// HORIZONTAL FIELDSET
		$horizontal_fieldset = new FormFieldsetHorizontal('fieldset5');
		$horizontal_fieldset->set_description($this->lang['form.horizontal.desc']);
		$form->add_fieldset($horizontal_fieldset);
		$horizontal_fieldset->add_field(new FormFieldTextEditor('texthor', $this->lang['form.input.text'], $this->lang['form.input.text.lorem'], array('required' => true, 'class' => 'form-field-css-class')));
		$horizontal_fieldset->add_field(new FormFieldCheckbox('cbvert', $this->lang['form.input.checkbox'], FormFieldCheckbox::CHECKED, array('class' => 'form-field-css-class')));

		// BUTTONS
		$buttons_fieldset = new FormFieldsetSubmit('buttons');
		$buttons_fieldset->add_element(new FormButtonReset());
		$this->preview_button = new FormButtonSubmit($this->lang['form.preview'], 'previewl', 'alert("Hello world preview")');
		$buttons_fieldset->add_element($this->preview_button);
		$this->submit_button = new FormButtonDefaultSubmit();
		$buttons_fieldset->add_element($this->submit_button);
		$buttons_fieldset->add_element(new FormButtonButton($this->lang['form.button'], 'alert("Hello world");'));
		$form->add_fieldset($buttons_fieldset);

		// FORM CONSTRAINTS
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));

		return $form;
	}

	private function check_authorizations()
	{
		if (!SandboxAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
