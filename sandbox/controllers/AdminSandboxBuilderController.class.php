<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 15
 * @since       PHPBoost 5.2 - 2020 05 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSandboxBuilderController extends DefaultAdminModuleController
{
	private $g_map_enabled;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$form = $this->build_form();

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
					'SELECT_LABELS' => $form->get_value('select')->get_label(),
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

		$this->view->put_all(array(
			'C_GMAP'          => $this->g_map_enabled,
			'CONTENT'         => $form->display(),
			'FORM_MARKUP'     => self::build_markup('sandbox/pagecontent/builder/form.tpl'),
			'INPUT_MARKUP'    => self::build_markup('sandbox/pagecontent/builder/input.tpl'),
			'TEXTAREA_MARKUP' => self::build_markup('sandbox/pagecontent/builder/textarea.tpl'),
			'CHECKBOX_MARKUP' => self::build_markup('sandbox/pagecontent/builder/checkbox.tpl'),
			'RADIO_MARKUP'    => self::build_markup('sandbox/pagecontent/builder/radio.tpl'),
			'SELECT_MARKUP'   => self::build_markup('sandbox/pagecontent/builder/select.tpl'),
			'DND_MARKUP'      => self::build_markup('sandbox/pagecontent/builder/dnd.tpl'),
			'BUTTON_MARKUP'   => self::build_markup('sandbox/pagecontent/builder/button.tpl'),
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu()
		));

		return new AdminSandboxDisplayResponse($this->view, $this->lang['sandbox.module.title'] . ' - ' . $this->lang['sandbox.forms']);
	}

	private function init()
	{
		$this->g_map_enabled = (ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key());
	}

	private function build_markup($tpl)
	{
		$view = new FileTemplate($tpl);
		$view->add_lang($this->lang);
		return $view;
	}

	private function build_form()
	{
		$security_config = SecurityConfig::load();
		$form = new HTMLForm('Sandbox_Builder');

		// TEXT FIELDS
		$text_fields = new FormFieldsetHTML('text_field', $this->lang['sandbox.builder.text.fields']);
			$form->add_fieldset($text_fields);

			// Text
			$text_fields->add_field(new FormFieldTextEditor('text', $this->lang['sandbox.builder.text.field'], $this->lang['sandbox.builder.text.field.lorem'],
				array('maxlength' => 25, 'description' => $this->lang['sandbox.builder.text.field.clue'], 'class' => 'css-class'),
				array(new FormFieldConstraintRegex('`^[a-z0-9_ ]+$`iu'))
			));

			// Url
			$text_fields->add_field(new FormFieldUrlEditor('siteweb', $this->lang['sandbox.builder.url.field'], $this->lang['sandbox.builder.url.field.placeholder'],
				array('description' => $this->lang['sandbox.builder.url.field.clue'], 'class' => 'css-class')
			));

			// Email
			$text_fields->add_field(new FormFieldMailEditor('email', $this->lang['sandbox.builder.email.field'], $this->lang['sandbox.builder.email.field.placeholder'],
				array('description' => $this->lang['sandbox.builder.email.field.clue'], 'class' => 'css-class')
			));
			$text_fields->add_field(new FormFieldMailEditor('multiple_email', $this->lang['sandbox.builder.email.field.multiple'], $this->lang['sandbox.builder.email.field.multiple.placeholder'],
				array('description' => $this->lang['sandbox.builder.email.field.multiple.clue'], 'multiple' => true, 'class' => 'css-class')
			));

			// Phone
			$text_fields->add_field(new FormFieldTelEditor('tel', $this->lang['sandbox.builder.phone.field'], $this->lang['sandbox.builder.phone.field.placeholder'],
				array('description' => $this->lang['sandbox.builder.phone.field.clue'], 'class' => 'css-class')
			));

			// Disabled
			$text_fields->add_field(new FormFieldTextEditor('text_disabled', $this->lang['sandbox.builder.text.field.disabled'], '',
				array('maxlength' => 25, 'description' => $this->lang['sandbox.builder.text.field.disabled.clue'], 'disabled' => true, 'class' => 'css-class')
			));

			// Readonly
			$text_fields->add_field(new FormFieldTextEditor('text_readonly', $this->lang['sandbox.builder.text.field.readonly'], '',
				array('maxlength' => 25, 'description' => $this->lang['sandbox.builder.text.field.disabled.clue'], 'readonly' => true, 'class' => 'css-class')
			));

			// Required
			$text_fields->add_field(new FormFieldTextEditor('required', $this->lang['sandbox.builder.text.field.required'], $this->lang['sandbox.builder.text.field.lorem'],
				array('maxlength' => 25, 'description' => $this->lang['sandbox.builder.text.field.required.filled'], 'required' => true, 'class' => 'css-class')
			));
			$text_fields->add_field(new FormFieldTextEditor('required_empty', $this->lang['sandbox.builder.text.field.required'], '',
				array('maxlength' => 25, 'description' => $this->lang['sandbox.builder.text.field.required.empty'], 'required' => true, 'class' => 'css-class')
			));

			// Number
			$text_fields->add_field(new FormFieldNumberEditor('number', $this->lang['sandbox.builder.number.field'], $this->lang['sandbox.builder.number.field.placeholder'],
				array('min' => 10, 'max' => 100, 'description' => $this->lang['sandbox.builder.number.field.clue'], 'class' => 'css-class'),
				array(new FormFieldConstraintIntegerRange(10, 100))
			));
			$text_fields->add_field(new FormFieldDecimalNumberEditor('decimal', $this->lang['sandbox.builder.number.field.decimal'], $this->lang['sandbox.builder.number.field.decimal.placeholder'],
				array('min' => 0, 'step' => 0.1, 'description' => $this->lang['sandbox.builder.number.field.decimal.clue'], 'class' => 'css-class')
			));

			// Password
			$text_fields->add_field($password = new FormFieldPasswordEditor('password', $this->lang['sandbox.builder.password.field'], $this->lang['sandbox.builder.password.field.placeholder'],
				array('description' => $security_config->get_internal_password_min_length() . $this->lang['sandbox.builder.password.field.clue'], 'class' => 'css-class'),
				array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()))
			));
			$text_fields->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['sandbox.builder.password.field.confirm'], $this->lang['sandbox.builder.password.field.placeholder'],
				array('description' => $security_config->get_internal_password_min_length() . $this->lang['sandbox.builder.password.field.clue'], 'class' => 'css-class'),
				array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()))
			));

		// TEXTAREA
		$textarea = new FormFieldsetHTML('textarea', $this->lang['sandbox.builder.textarea']);
			$form->add_fieldset($textarea);

			// Short multi line text
			$textarea->add_field(new FormFieldShortMultiLineTextEditor('short_multi_line_text', $this->lang['sandbox.builder.multiline.medium'], $this->lang['sandbox.builder.multiline.lorem'],
				array('rows' => 3, 'required' => true, 'class' => 'css-class')
			));

			// Multi line text
			$textarea->add_field(new FormFieldMultiLineTextEditor('multi_line_text', $this->lang['sandbox.builder.multiline'], $this->lang['sandbox.builder.multiline.lorem'],
				array('rows' => 6, 'cols' => 47, 'description' => $this->lang['sandbox.builder.multiline.clue'], 'required' => true, 'class' => 'css-class')
			));

			// Rich text
			$textarea->add_field(new FormFieldRichTextEditor('rich_text', $this->lang['sandbox.builder.rich.text'], $this->lang['sandbox.builder.rich.text.placeholder'],
				array('required' => true, 'class' => 'css-class')
			));

		// RADIO / CHECKBOX
		$choices = new FormFieldsetHTML('choices', $this->lang['sandbox.builder.checked.choices']);
			$form->add_fieldset($choices);

			// Checkboxes
			$choices->add_field(new FormFieldCheckbox('checkbox', $this->lang['sandbox.builder.checkbox'], FormFieldCheckbox::CHECKED,
				array('class' => 'custom-checkbox css-class')
			));
			$choices->add_field(new FormFieldMultipleCheckbox('multiple_check_box', $this->lang['sandbox.builder.multiple.checkbox'],
				array('1'),
				array(
					new FormFieldMultipleCheckboxOption('1', $this->lang['sandbox.builder.choice'].'1'),
					new FormFieldMultipleCheckboxOption('2', $this->lang['sandbox.builder.choice'].'2')
				),
				array('required' => true, 'class' => 'mini-checkbox css-class')
			));

			// Radios
			$default_option = new FormFieldRadioChoiceOption($this->lang['sandbox.builder.choice'].'1', '1');
			$choices->add_field(new FormFieldRadioChoice('inline_radio', $this->lang['sandbox.builder.radio'] . ' inline', '',
				array(
					$default_option,
					new FormFieldRadioChoiceOption($this->lang['sandbox.builder.choice'].'2', '2')
				),
				array('required' => true, 'class' => 'css-class inline-radio custom-radio')
			));
			$default_option = new FormFieldRadioChoiceOption($this->lang['sandbox.builder.choice'].'1', '1');
			$choices->add_field(new FormFieldRadioChoice('radio', $this->lang['sandbox.builder.radio'], '',
				array(
					$default_option,
					new FormFieldRadioChoiceOption($this->lang['sandbox.builder.choice'].'2', '2')
				),
				array('required' => true, 'class' => 'custom-radio css-class')
			));

		// SELECTORS
		$select = new FormFieldsetHTML('selects', $this->lang['sandbox.builder.selects']);
			$form->add_fieldset($select);

			// SELECT
			$select->add_field(new FormFieldSimpleSelectChoice('select', $this->lang['sandbox.builder.select'], '',
				array(
					new FormFieldSelectChoiceOption(' ', '0'),
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'1', '1'),
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'2', '2'),
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'3', '3'),
					new FormFieldSelectChoiceGroupOption($this->lang['sandbox.builder.choice.group'].'1',
						array(
							new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'4', '4'),
							new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'5', '5'),
						)
					),
					new FormFieldSelectChoiceGroupOption($this->lang['sandbox.builder.choice.group'].'2',
						array(
							new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'6', '6'),
							new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'7', '7'),
						)
					)
				),
				array('required' => true, 'class' => 'top-field css-class')
			));

			$select->add_field(new FormFieldMultipleSelectChoice('multiple_select', $this->lang['sandbox.builder.multiple.select'],
				array('1', '2'),
				array(
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'1', '1'),
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'2', '2'),
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'3', '3')
				),
				array('required' => true, 'class' => 'top-field css-class')
			));

			// Fake select
			$select->add_field(new FormFieldSimpleSelectChoice('fake_select', $this->lang['sandbox.builder.select.to.list'],
				array('1'),
				array(
					new FormFieldSelectChoiceOption('&nbsp;', '0'),
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'1', '1', array('selected' => true, 'data_option_icon' => 'far fa-id-card')),
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'2', '2', array('data_option_icon' => 'far fa-id-card')),
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'3', '3', array('data_option_icon' => 'far fa-id-card')),
				),
				array('class' => 'top-field css-class', 'select_to_list' => true)
			));
			$select->add_field(new FormFieldMultipleSelectChoice('fake_multiple_select', $this->lang['sandbox.builder.multiple.select.to.list'],
				array(),
				array(
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'1', '1', array('data_option_class' => 'bgc-full question', 'data_option_icon' => 'far fa-id-card')),
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'2', '2', array('data_option_class' => 'bgc error', 'disable' => true)),
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'3', '3', array('data_option_class' => 'indent')),
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'4', '4', array('data_option_class' => 'bgc-full question', 'selected' => true, 'data_option_icon' => 'far fa-id-card')),
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'5', '5', array('data_option_img' => '../templates/__default__/theme/images/logo.svg')),
					new FormFieldSelectChoiceOption($this->lang['sandbox.builder.choice'].'6', '6')
				),
				array('class' => 'css-class', 'multiple_select_to_list' => true)
			));

			// Autocomplete
			$select->add_field(new FormFieldTimezone('timezone', $this->lang['sandbox.builder.timezone'], 'UTC+0',
				array('class' => 'top-field css-class')
			));

			$select->add_field(new FormFieldAjaxSearchUserAutoComplete('user_completition', $this->lang['sandbox.builder.user.completion'], '',
				array('class' => 'css-class')
			));

		// MISCELLANEOUS
		$miscellaneous = new FormFieldsetHTML('miscellaneous', $this->lang['sandbox.miscellaneous']);
			$form->add_fieldset($miscellaneous);

			// HIDDEN
			$miscellaneous->add_field(new FormFieldHidden('hidden', $this->lang['sandbox.builder.hidden']));

			// Description
			$miscellaneous->set_description($this->lang['sandbox.builder.clue']);

			// Separator
			$miscellaneous->add_field(new FormFieldSpacer('spacer', '<span class="smaller">' . $this->lang['sandbox.builder.spacer'] . '</span>', array('class' => 'css-class')));

			// Free field
			$miscellaneous->add_field(new FormFieldFree('free', $this->lang['sandbox.builder.free.html'], $this->lang['sandbox.builder.text.field.lorem'],
				array('class' => 'css-class')
			));

			// Range
			$miscellaneous->add_field($password = new FormFieldRangeEditor('range', $this->lang['sandbox.builder.slider.field'], $this->lang['sandbox.builder.slider.field.placeholder'],
				array('min' => 1, 'max' => 10, 'description' => $this->lang['sandbox.builder.slider.field.clue'], 'class' => 'css-class')
			));

			// Date
			$miscellaneous->add_field(new FormFieldDate('date', $this->lang['sandbox.builder.date'], null,
				array('required' => true, 'class' => 'css-class')
			));

			// Date time
			$miscellaneous->add_field(new FormFieldDateTime('date_time', $this->lang['sandbox.builder.date.hm'], null,
				array('required' => true, 'class' => 'css-class')
			));

			// Possible values
			$miscellaneous->add_field(new FormFieldPossibleValues('possible_values_inputs', $this->lang['sandbox.builder.possible.values'], array(),
				array('class' => 'css-class')
			));

			// Sources
			$miscellaneous->add_field(new FormFieldSelectSources('select_sources', $this->lang['sandbox.builder.sources'], array(),
				array('class' => 'css-class')
			));

			// Color picker
			$miscellaneous->add_field(new FormFieldColorPicker('color', $this->lang['sandbox.builder.color'], '#366393',
				array('class' => 'top-field css-class')
			));

			// Search
			$miscellaneous->add_field(new FormFieldSearch('search', $this->lang['sandbox.builder.search'], '',
				array('class' => 'top-field css-class')
			));

			// File picker
			$miscellaneous->add_field(new FormFieldFilePicker('file', $this->lang['sandbox.builder.file.picker'],
				array('class' => 'top-field css-class')
			));

			// Multiple file picker
			$miscellaneous->add_field(new FormFieldFilePicker('multiple_files', $this->lang['sandbox.builder.multiple.file.picker'],
				array('class' => 'top-field css-class', 'multiple' => true)
			));

			// Thumbnail
			$miscellaneous->add_field(new FormFieldThumbnail('thumbnail', $this->lang['sandbox.builder.thumbnail.picker'], '', FormFieldThumbnail::get_default_thumbnail_url(UserAccountsConfig::NO_AVATAR_URL),
				array('class' => 'top-field css-class')
			));

			// Upload file
			$miscellaneous->add_field(new FormFieldUploadFile('upload_file', $this->lang['sandbox.builder.file.upload'], '',
				array('required' => true, 'class' => 'top-field css-class')
			));

		// GOOGLE MAPS
		if ($this->g_map_enabled)
		{
			$fieldset_maps = new FormFieldsetHTML('fieldset_maps', $this->lang['sandbox.builder.googlemap']);
			$form->add_fieldset($fieldset_maps);

			// Simple address
			$fieldset_maps->add_field(new GoogleMapsFormFieldSimpleAddress('simple_address', $this->lang['sandbox.builder.googlemap.simple.address'], '', array('class' => 'css-class')));

			// Map address
			$fieldset_maps->add_field(new GoogleMapsFormFieldMapAddress('map_address', $this->lang['sandbox.builder.googlemap.map.address'], '', array('class' => 'css-class', 'include_api' => false)));

			// Simple marker
			$fieldset_maps->add_field(new GoogleMapsFormFieldSimpleMarker('simple_marker', $this->lang['sandbox.builder.googlemap.simple.marker'], '', array('class' => 'css-class', 'include_api' => false)));

			// Multiple markers
			$fieldset_maps->add_field(new GoogleMapsFormFieldMultipleMarkers('multiple_markers', $this->lang['sandbox.builder.googlemap.multiple.markers'], '', array('class' => 'css-class', 'include_api' => false)));
		}

		// AUTH
		$authorizations = new FormFieldsetHTML('authorizations', $this->lang['sandbox.builder.authorization']);
			$auth_settings = new AuthorizationsSettings(
				array(
					new ActionAuthorization($this->lang['sandbox.builder.authorization.1'], 1, $this->lang['sandbox.builder.authorization.1.clue']),
					new ActionAuthorization($this->lang['sandbox.builder.authorization.2'], 2))
				);
			$auth_settings->build_from_auth_array(array('r1' => 3, 'r0' => 2, 'm1' => 1, 1 => 2));
			$auth_setter = new FormFieldAuthorizationsSetter('auth', $auth_settings);
			$authorizations->add_field($auth_setter);
			$form->add_fieldset($authorizations);

		// VERTICAL FIELDSET
		$vertical_fieldset = new FormFieldsetVertical('vertical_fieldset');
			$vertical_fieldset->set_description($this->lang['sandbox.builder.vertical.clue']);
			$form->add_fieldset($vertical_fieldset);
			$vertical_fieldset->add_field(new FormFieldTextEditor('alone', $this->lang['sandbox.builder.text.field'], $this->lang['sandbox.builder.text.field.lorem'], array('class' => 'css-class')));
			$vertical_fieldset->add_field(new FormFieldCheckbox('cbhor', $this->lang['sandbox.builder.checkbox'], FormFieldCheckbox::UNCHECKED, array('class' => 'css-class')));

		// HORIZONTAL FIELDSET
		$horizontal_fieldset = new FormFieldsetHorizontal('horizontal_fieldset');
			$horizontal_fieldset->set_description($this->lang['sandbox.builder.horizontal.clue']);
			$form->add_fieldset($horizontal_fieldset);
			$horizontal_fieldset->add_field(new FormFieldTextEditor('texthor', $this->lang['sandbox.builder.text.field'], $this->lang['sandbox.builder.text.field.lorem'], array('required' => true, 'class' => 'css-class')));
			$horizontal_fieldset->add_field(new FormFieldCheckbox('cbvert', $this->lang['sandbox.builder.checkbox'], FormFieldCheckbox::CHECKED, array('class' => 'css-class')));

		// BUTTONS
		$buttons = new FormFieldsetHTML('buttons', $this->lang['sandbox.builder.buttons']);
			$form->add_fieldset($buttons);
			$buttons->set_css_class('no-flex');
			$buttons->add_field(new FormFieldSpacer('all_buttons_explain', $this->lang['sandbox.builder.all.buttons']));

			$buttons->add_field(new FormFieldSpacer('button_sizes', $this->lang['sandbox.builder.button.sizes']));
			$buttons->add_element(new FormButtonButton('.smallest', '', 'smallest-button', 'smallest'));
			$buttons->add_element(new FormButtonButton('.biggest', '', 'biggest-button', 'biggest'));

			$buttons->add_field(new FormFieldSpacer('button_warning', $this->lang['sandbox.builder.button.colors']));
			$buttons->add_element(new FormButtonButton('.warning', '', 'warning-button', 'warning'));
			$buttons->add_element(new FormButtonButton('.warning.bgc', '', 'bgc-warning-button', 'bgc warning'));
			$buttons->add_element(new FormButtonButton('.warning.bgc-full', '', 'bgc-full-warning-button', 'bgc-full warning'));

			$buttons->add_field(new FormFieldSpacer('alternate_buttons', $this->lang['sandbox.builder.button.link']));
			$buttons->add_element(new FormButtonLink($this->lang['sandbox.builder.button.picture'], 'https://www.phpboost.com', Url::to_rel('/templates/__default__/theme/images/logo.svg'), '', ''));
			$buttons->add_element(new FormButtonLink($this->lang['sandbox.builder.button.confirm'], 'https://www.phpboost.com', '', 'bgc-full question button', $this->lang['sandbox.builder.button.confirm.alert']));
			$buttons->add_element(new FormButtonLinkCssImg('Button', 'https://www.phpboost.com','fa fa-share', $this->lang['sandbox.builder.button.icon']));

			$buttons->add_field(new FormFieldSpacer('alternate_submit_buttons', $this->lang['sandbox.builder.button.alternate.send']));
			$buttons->add_element(new FormButtonSubmitCssImg($this->lang['sandbox.builder.button.icon'], 'fa fa-check', 'Submit', ''));
			$buttons->add_element(new FormButtonSubmitImg($this->lang['sandbox.builder.button.picture'], Url::to_rel('/templates/__default__/theme/images/logo.svg'), ''));

		// SUBMIT BUTTONS
		$buttons_fieldset = new FormFieldsetSubmit('button_submit');
			$buttons_fieldset->add_element(new FormButtonReset());
			$this->preview_button = new FormButtonSubmit($this->lang['sandbox.builder.preview'], 'previewl', 'alert("Hello world preview")');
			$buttons_fieldset->add_element($this->preview_button);
			$this->submit_button = new FormButtonDefaultSubmit();
			$buttons_fieldset->add_element($this->submit_button);
			$buttons_fieldset->add_element(new FormButtonButton($this->lang['sandbox.builder.button'], 'alert("Hello world");', '', 'button'));
			$form->add_fieldset($buttons_fieldset);

		// FORM CONSTRAINTS
		// $form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));

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
