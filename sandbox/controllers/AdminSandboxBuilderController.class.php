<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 05
 * @since       PHPBoost 5.2 - 2019 11 01
*/

class AdminSandboxBuilderController extends AdminModuleController
{
	private $view;
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

		$this->view->put_all(array('C_GMAP' => $c_gmap));

		if ($this->submit_button->has_been_submited() || $this->preview_button->has_been_submited())
		{
			if ($form->validate())
			{
				$this->view->put_all(array(
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
					$this->view->put_all(array('FILE' => $file->get_name() . ' - ' . $file->get_size() . 'b - ' . $file->get_mime_type()));
				}
			}
		}

		$this->view->put('form', $form->display());

		return new AdminSandboxDisplayResponse($this->view, $this->lang['builder.title']);
	}

	private function init()
	{
		$this->common_lang = LangLoader::get('common', 'sandbox');
		$this->lang = LangLoader::get('builder', 'sandbox');
		$this->view = new FileTemplate('sandbox/AdminSandboxBuilderController.tpl');
		$this->view->add_lang($this->common_lang);
		$this->view->add_lang($this->lang);
	}

	private function build_form()
	{
		$security_config = SecurityConfig::load();
		$form = new HTMLForm('sandbox_Builder');

		// FIELDSET
		$fieldset = new FormFieldsetHTML('fieldset_1', $this->lang['builder.title']);
		$form->add_fieldset($fieldset);

		// FIELDSET TEXT
		$fieldset_text = new FormFieldsetHTML('fieldset__text', $this->lang['builder.title.inputs']);
			$form->add_fieldset($fieldset_text);

			// Single line text
			$fieldset_text->add_field(new FormFieldTextEditor('text', $this->lang['builder.input.text'], $this->lang['builder.input.text.lorem'],
				array(
					'maxlength' => 25, 'class' => 'css-class',
					'description' => $this->lang['builder.input.text.desc']
				),
				array(new FormFieldConstraintRegex('`^[a-z0-9_ ]+$`iu'))
			));

			$fieldset_text->add_field(new FormFieldTextEditor('textdisabled', $this->lang['builder.input.text.disabled'], '',
				array(
					'maxlength' => 25, 'disabled' => true, 'class' => 'css-class',
					'description' => $this->lang['builder.input.text.disabled.desc']
				)
			));

			$fieldset_text->add_field(new FormFieldUrlEditor('siteweb', $this->lang['builder.input.url'], $this->lang['builder.input.url.placeholder'],
				array(
					'class' => 'css-class',
					'description' => $this->lang['builder.input.url.desc']
				)
			));

			// RANGE
			$fieldset_text->add_field($password = new FormFieldRangeEditor('range', $this->lang['builder.input.length'], $this->lang['builder.input.length.placeholder'],
				array(
					'min' => 1, 'max' => 10, 'class' => 'css-class',
					'description' => $this->lang['builder.input.length.desc']
				)
			));

			$fieldset_text->add_field(new FormFieldMailEditor('mail', $this->lang['builder.input.email'], $this->lang['builder.input.email.placeholder'],
				array(
					'class' => 'css-class',
					'description' => $this->lang['builder.input.email.desc'],
				)
			));

			$fieldset_text->add_field(new FormFieldMailEditor('mail_multiple', $this->lang['builder.input.email.multiple'], $this->lang['builder.input.email.multiple.placeholder'],
				array(
					'multiple' => true, 'class' => 'css-class',
					'description' => $this->lang['builder.input.email.multiple.desc']
				)
			));
			$fieldset_text->add_field(new FormFieldTextEditor('text2', $this->lang['builder.input.text.required'], $this->lang['builder.input.text.lorem'],
				array(
					'maxlength' => 25, 'required' => true, 'class' => 'css-class',
					'description' => $this->lang['builder.input.text.required.filled']
				)
			));

			$fieldset_text->add_field(new FormFieldTextEditor('text3', $this->lang['builder.input.text.required'], '',
				array(
					'maxlength' => 25, 'required' => true, 'class' => 'css-class',
					'description' => $this->lang['builder.input.text.required.empty']
				)
			));

			$fieldset_text->add_field(new FormFieldTelEditor('tel', $this->lang['builder.input.phone'], $this->lang['builder.input.phone.placeholder'],
				array(
					'class' => 'css-class',
					'description' => $this->lang['builder.input.phone.desc']
				)
			));

			$fieldset_text->add_field(new FormFieldNumberEditor('age', $this->lang['builder.input.number'], $this->lang['builder.input.number.placeholder'],
				array(
					'min' => 10, 'max' => 100, 'class' => 'css-class',
					'description' => $this->lang['builder.input.number.desc']
				),
				array(new FormFieldConstraintIntegerRange(10, 100))
			));

			$fieldset_text->add_field(new FormFieldDecimalNumberEditor('decimal', $this->lang['builder.input.number.decimal'], $this->lang['builder.input.number.decimal.placeholder'],
				array(
					'min' => 0, 'step' => 0.1, 'class' => 'css-class',
					'description' => $this->lang['builder.input.number.decimal.desc']
				)
			));

			$fieldset_text->add_field(new FormFieldSpacer('password_separator', ''));

			// PASSWORD
			$fieldset_text->add_field($password = new FormFieldPasswordEditor('password', $this->lang['builder.input.password'], $this->lang['builder.input.password.placeholder'],
				array(
					'class' => 'css-class',
					'description' => $security_config->get_internal_password_min_length() . $this->lang['builder.input.password.desc']
				),
				array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()))
			));

			$fieldset_text->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['builder.input.password.confirm'], $this->lang['builder.input.password.placeholder'],
				array(
					'class' => 'css-class',
					'description' => $security_config->get_internal_password_min_length() . $this->lang['builder.input.password.desc']
				),
				array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()))
			));

		// TEXTAREA
		$textarea = new FormFieldsetHTML('fieldset__textarea', $this->lang['builder.input.multiline']);
			$form->add_fieldset($textarea);

			// Short multi line text
			$textarea->add_field(new FormFieldShortMultiLineTextEditor('short_multi_line_text', $this->lang['builder.input.multiline.medium'], $this->lang['builder.input.multiline.lorem'],
				array('rows' => 3, 'required' => true, 'class' => 'css-class')
			));

			// Multi line text
			$textarea->add_field(new FormFieldMultiLineTextEditor('multi_line_text', $this->lang['builder.input.multiline'], $this->lang['builder.input.multiline.lorem'],
				array(
					'rows' => 6, 'cols' => 47, 'required' => true, 'class' => 'css-class',
					'description' => $this->lang['builder.input.multiline.desc']
				)
			));

			// Rich text
			$textarea->add_field(new FormFieldRichTextEditor('rich_text', $this->lang['builder.input.rich.text'], $this->lang['builder.input.rich.text.placeholder'],
				array('required' => true, 'class' => 'css-class')
			));

		// CHOICES
		$choices = new FormFieldsetHTML('fieldset__choices', $this->lang['builder.input.choices']);
			$form->add_fieldset($choices);

			// Checkbox
			$choices->add_field(new FormFieldCheckbox('checkbox', $this->lang['builder.input.checkbox'], FormFieldCheckbox::CHECKED,
				array('class' => 'top-field css-class')
			));

			// Custom Checkbox
			$choices->add_field(new FormFieldCheckbox('custom_checkbox', $this->lang['builder.input.checkbox'], FormFieldCheckbox::CHECKED,
				array('description' => 'custom', 'class' => 'top-field custom-checkbox css-class')
			));

			// Mini Checkbox
			$choices->add_field(new FormFieldCheckbox('mini_checkbox', $this->lang['builder.input.checkbox'], FormFieldCheckbox::CHECKED,
				array('description' => 'mini', 'class' => 'top-field mini-checkbox css-class')
			));

			// Multiple checkboxes
			$choices->add_field(new FormFieldMultipleCheckbox('multiple_checkbox', $this->lang['builder.input.multiple.checkbox'],
				array('1'),
				array(
					new FormFieldMultipleCheckboxOption('1', $this->lang['builder.input.choice'].' 1'),
					new FormFieldMultipleCheckboxOption('2', $this->lang['builder.input.choice'].' 2')
				),
				array(
					'required' => true, 'class' => 'mini-checkbox css-class',
					'description' => 'mini'
				)
			));

			// Multiple inline checkboxes
			$choices->add_field(new FormFieldMultipleCheckbox('inline_multiple_checkbox', $this->lang['builder.input.multiple.checkbox'],
				array('1'),
				array(
					new FormFieldMultipleCheckboxOption('1', $this->lang['builder.input.choice'].' 1'),
					new FormFieldMultipleCheckboxOption('2', $this->lang['builder.input.choice'].' 2')
				),
				array('description' => 'inline - mini', 'required' => true, 'class' => 'inline-checkbox mini-checkbox css-class')
			));

			// Separator
			$choices->add_field(new FormFieldSpacer('radio_separator', ''));

			// Inline radio inputs
			$default_option = new FormFieldRadioChoiceOption($this->lang['builder.input.choice'].' 1', '1');
			$choices->add_field(new FormFieldRadioChoice('inline_radio', $this->lang['builder.input.radio'], '',
				array(
					$default_option,
					new FormFieldRadioChoiceOption($this->lang['builder.input.choice'].' 2', '2')
				),
				array('description' => 'inline', 'required' => true, 'class' => 'top-field css-class inline-radio')
			));

			// Inline custom radio inputs
			$default_option = new FormFieldRadioChoiceOption($this->lang['builder.input.choice'].' 1', '1');
			$choices->add_field(new FormFieldRadioChoice('inline_custom_radio', $this->lang['builder.input.radio'], '',
				array(
					$default_option,
					new FormFieldRadioChoiceOption($this->lang['builder.input.choice'].' 2', '2')
				),
				array('description' => 'inline - custom', 'required' => true, 'class' => 'top-field css-class inline-radio custom-radio')
			));

			// Custom radio inputs
			$default_option = new FormFieldRadioChoiceOption($this->lang['builder.input.choice'].' 1', '1');
			$choices->add_field(new FormFieldRadioChoice('radio', $this->lang['builder.input.radio'], '',
				array(
					$default_option,
					new FormFieldRadioChoiceOption($this->lang['builder.input.choice'].' 2', '2')
				),
				array('description' => 'custom', 'required' => true, 'class' => 'css-class custom-radio')
			));

			// Separator
			$choices->add_field(new FormFieldSpacer('select_separator', ''));

			// Select
			$choices->add_field(new FormFieldSimpleSelectChoice('select', $this->lang['builder.input.select'], '',
				array(
					new FormFieldSelectChoiceOption('', ''),
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].' 1', '1'),
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].' 2', '2'),
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].' 3', '3'),
					new FormFieldSelectChoiceGroupOption($this->lang['builder.input.choice.group'].' 1',
						array(
							new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].' 4', '4'),
							new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].' 5', '5'),
						)
					),
					new FormFieldSelectChoiceGroupOption($this->lang['builder.input.choice.group'].' 2',
						array(
							new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].' 6', '6'),
							new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].' 7', '7'),
						)
					)
				),
				array('required' => true, 'class' => 'top-field css-class')
			));

			// Select multiple
			$choices->add_field(new FormFieldMultipleSelectChoice('multiple_select', $this->lang['builder.input.multiple.select'],
				array('1', '2'),
				array(
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].' 1', '1'),
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].' 2', '2'),
					new FormFieldSelectChoiceOption($this->lang['builder.input.choice'].' 3', '3')
				),
				array('required' => true, 'class' => 'css-class')
			));

			// Timezone
			$choices->add_field(new FormFieldTimezone('timezone', $this->lang['builder.input.timezone'], 'UTC+0',
				array('class' => 'top-field css-class')
			));

			// User Autocompletion
			$choices->add_field(new FormFieldAjaxSearchUserAutoComplete('user_completition', $this->lang['builder.input.user.completion'], '',
				array('class' => 'top-field css-class')
			));

		// BUTTONS
		$buttons = new FormFieldsetHTML('all_buttons', $this->lang['builder.buttons']);
			$buttons->set_description($this->lang['builder.all.buttons']);
			$form->add_fieldset($buttons);

			$buttons->add_element(new FormButtonButton('.reset-button', '', '', 'reset-button'));
			$buttons->add_element(new FormButtonButton('.preview-button', '', '', 'preview-button'));
			$buttons->add_element(new FormButtonButton('none', '', '', ''));
			$buttons->add_element(new FormButtonButton('.alt-button', '', '', 'alt-button'));
			$buttons->add_element(new FormButtonButton('.submit', '', '', 'submit'));
			$buttons->add_element(new FormButtonButton('.alt-submit', '', '', 'alt-submit'));


		$miscellaneous = new FormFieldsetHTML('fieldset2', $this->lang['builder.title.miscellaneous']);
			$form->add_fieldset($miscellaneous);

			$miscellaneous->set_description($this->lang['builder.desc']);

			// Separator
			$miscellaneous->add_field(new FormFieldSpacer('form_separator', '<span class="smaller">' . $this->lang['builder.spacer'] . '</span>'));

			// Subtitle
			$miscellaneous->add_field(new FormFieldSubTitle('checkbox_subtitle', $this->lang['builder.subtitle'], ''));

			// CAPTCHA
			$miscellaneous->add_field(new FormFieldCaptcha('Captcha'));

			// HIDDEN
			$miscellaneous->add_field(new FormFieldHidden('hidden', $this->lang['builder.input.hidden']));

			// FREE FIELD
			$miscellaneous->add_field(new FormFieldFree('free', $this->lang['builder.free.html'], $this->lang['builder.input.text.lorem'],
				array('class' => 'css-class')
			));

			// DATE
			$miscellaneous->add_field(new FormFieldDate('date', $this->lang['builder.date'], null,
				array('required' => true, 'class' => 'css-class')
			));

			// DATE TIME
			$miscellaneous->add_field(new FormFieldDateTime('date_time', $this->lang['builder.date.hm'], null,
				array('required' => true, 'class' => 'half-field css-class')
			));

			// COLOR PICKER
			$miscellaneous->add_field(new FormFieldColorPicker('color', $this->lang['builder.color'], '#366393',
				array('class' => 'css-class')
			));

			// SEARCH
			$miscellaneous->add_field(new FormFieldSearch('search', $this->lang['builder.search'], '',
				array('class' => 'css-class')
			));

			// Separator
			$miscellaneous->add_field(new FormFieldSpacer('04_separator', ''));

			// FILE PICKER
			$miscellaneous->add_field(new FormFieldFilePicker('file', $this->lang['builder.file.picker'],
				array('class' => 'half-field css-class')
			));

			// MULTIPLE FILE PICKER
			$miscellaneous->add_field(new FormFieldFilePicker('multiple_files', $this->lang['builder.multiple.file.picker'],
				array('class' => 'half-field css-class', 'multiple' => true)
			));

			// UPLOAD FILE
			$miscellaneous->add_field(new FormFieldUploadFile('upload_file', $this->lang['builder.file.upload'], '',
				array('required' => true, 'class' => 'half-field top-field css-class')
			));

			// Separator
			$miscellaneous->add_field(new FormFieldSpacer('05_separator', ''));

			// List actionLinks
			// Subtitle
			$miscellaneous->add_field(new FormFieldSubTitle('links_subtitle', $this->lang['builder.links.menu'], ''));

			$miscellaneous->add_field(new FormFieldActionLinkList('actionlink_list',
				array(
					new FormFieldActionLinkElement($this->lang['builder.link.icon'], '#', 'fa-share'),
					new FormFieldActionLinkElement($this->lang['builder.link.img'], '#', '', PATH_TO_ROOT.'/sandbox/sandbox_mini.png'),
					new FormFieldActionLinkElement($this->lang['builder.link'].' 3', '#', ''),
					new FormFieldActionLinkElement($this->lang['builder.link'].' 4', '#', '')
				),
				array('class' => 'css-class')
			));

		// GOOGLE MAPS
		if (ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key())
		{
			$fieldset_maps = new FormFieldsetHTML('fieldset_maps', $this->lang['builder.googlemap']);
			$form->add_fieldset($fieldset_maps);

			// SIMPLE ADDRESS
			$fieldset_maps->add_field(new GoogleMapsFormFieldSimpleAddress('simple_address', $this->lang['builder.googlemap.simple_address'], '',
				array('class' => 'top-field half-field css-class')
			));

			// MAP ADDRESS
			$fieldset_maps->add_field(new GoogleMapsFormFieldMapAddress('map_address', $this->lang['builder.googlemap.map_address'], '',
				array('class' => 'top-field half-field css-class', 'include_api' => false)
			));

			// SIMPLE MARKER
			$fieldset_maps->add_field(new GoogleMapsFormFieldSimpleMarker('simple_marker', $this->lang['builder.googlemap.simple_marker'], '',
				array('class' => 'top-field half-field css-class', 'include_api' => false)
			));

			// MULTIPLE MARKERS
			$fieldset_maps->add_field(new GoogleMapsFormFieldMultipleMarkers('multiple_markers', $this->lang['builder.googlemap.multiple_markers'], '',
				array('class' => 'top-field half-field css-class', 'include_api' => false)
			));
		}

		// AUTH
		$fieldset3 = new FormFieldsetHTML('fieldset3', $this->lang['builder.authorization']);
			$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization($this->lang['builder.authorization.1'], 1, $this->lang['builder.authorization.1.desc']), new ActionAuthorization($this->lang['builder.authorization.2'], 2)));
			$auth_settings->build_from_auth_array(array('r1' => 3, 'r0' => 2, 'm1' => 1, '1' => 2));
			$auth_setter = new FormFieldAuthorizationsSetter('auth', $auth_settings);
			$fieldset3->add_field($auth_setter);
			$form->add_fieldset($fieldset3);

		// VERTICAL FIELDSET
		$vertical_fieldset = new FormFieldsetVertical('fieldset4');
			$vertical_fieldset->set_description($this->lang['builder.vertical.desc']);
			$form->add_fieldset($vertical_fieldset);
			$vertical_fieldset->add_field(new FormFieldTextEditor('alone', $this->lang['builder.input.text'], $this->lang['builder.input.text.lorem'], array('class' => 'css-class')));
			$vertical_fieldset->add_field(new FormFieldCheckbox('cbhor', $this->lang['builder.input.checkbox'], FormFieldCheckbox::UNCHECKED, array('class' => 'css-class')));

		// HORIZONTAL FIELDSET
		$horizontal_fieldset = new FormFieldsetHorizontal('fieldset5');
		$horizontal_fieldset->set_description($this->lang['builder.horizontal.desc']);
		$form->add_fieldset($horizontal_fieldset);
		$horizontal_fieldset->add_field(new FormFieldTextEditor('texthor', $this->lang['builder.input.text'], $this->lang['builder.input.text.lorem'], array('required' => true, 'class' => 'css-class')));
		$horizontal_fieldset->add_field(new FormFieldCheckbox('cbvert', $this->lang['builder.input.checkbox'], FormFieldCheckbox::CHECKED, array('class' => 'css-class')));

		// BUTTONS
		$buttons_fieldset = new FormFieldsetSubmit('buttons');
		$buttons_fieldset->add_element(new FormButtonReset());
		$this->preview_button = new FormButtonSubmit($this->lang['builder.preview'], 'previewl', 'alert("Hello world preview")');
		$buttons_fieldset->add_element($this->preview_button);
		$this->submit_button = new FormButtonDefaultSubmit();
		$buttons_fieldset->add_element($this->submit_button);
		$buttons_fieldset->add_element(new FormButtonButton($this->lang['builder.button'], 'alert("Hello world");'));
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
