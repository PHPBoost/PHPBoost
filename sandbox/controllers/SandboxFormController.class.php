<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 01
 * @since       PHPBoost 3.0 - 2009 12 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxFormController extends ModuleController
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

		return $this->generate_response();
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

		// CHECKBOX
		$fieldset->add_field(new FormFieldCheckbox('checkbox', $this->lang['form.input.checkbox'], FormFieldCheckbox::CHECKED,
			array('class' => 'form-field-css-class')
		));

		// MULTIPLE CHECKBOXES
		$fieldset->add_field(new FormFieldMultipleCheckbox('multiple_check_box', $this->lang['form.input.multiple.checkbox'],
			array('1'),
			array(
				new FormFieldMultipleCheckboxOption('1', $this->lang['form.input.choice.1']),
				new FormFieldMultipleCheckboxOption('2', $this->lang['form.input.choice.2'])
			),
			array('required' => true, 'class' => 'form-field-css-class')
		));

		// RADIO
		$default_option = new FormFieldRadioChoiceOption($this->lang['form.input.choice.1'], '1');
		$fieldset->add_field(new FormFieldRadioChoice('inline_radio', $this->lang['form.input.radio'] . ' inline', '',
			array(
				$default_option,
				new FormFieldRadioChoiceOption($this->lang['form.input.choice.2'], '2')
			),
			array('required' => true, 'class' => 'form-field-css-class inline-radio')
		));

		// RADIO
		$default_option = new FormFieldRadioChoiceOption($this->lang['form.input.choice.1'], '1');
		$fieldset->add_field(new FormFieldRadioChoice('radio', $this->lang['form.input.radio'], '',
			array(
				$default_option,
				new FormFieldRadioChoiceOption($this->lang['form.input.choice.2'], '2')
			),
			array('required' => true, 'class' => 'form-field-css-class')
		));

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
			array('required' => true, 'class' => 'form-field-css-class')
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
			array('class' => 'form-field-css-class')
		));

		$fieldset->add_field(new FormFieldAjaxSearchUserAutoComplete('user_completition', $this->lang['form.input.user.completion'], '',
			array('class' => 'form-field-css-class')
		));

		// Separator
		$fieldset->add_field(new FormFieldFree('03_separator', '', ''));

		// BUTTONS
		$fieldset->add_field(new FormFieldFree('all_buttons', 'All buttons must have the <span class="pinned notice">.button</span> class.', ''));
		// Buttons
		$fieldset->add_element(new FormButtonButton('.reset', '', '', 'button reset'));
		$fieldset->add_element(new FormButtonButton('.button', '', '', 'button'));
		$fieldset->add_element(new FormButtonButton('.alt-button', '', '', 'button alt-button'));
		$fieldset->add_element(new FormButtonButton('.submit', '', '', 'button submit'));
		$fieldset->add_element(new FormButtonButton('.alt-submit', '', '', 'button alt-submit'));

		$fieldset->add_field(new FormFieldFree('button_sizes', '', ''));

		$fieldset->add_element(new FormButtonButton('.smallest', '', '', 'smallest'));
		$fieldset->add_element(new FormButtonButton('.smaller', '', '', 'smaller'));
		$fieldset->add_element(new FormButtonButton('.small', '', '', 'small'));
		$fieldset->add_element(new FormButtonButton('.button', '', '', 'button'));
		$fieldset->add_element(new FormButtonButton('.big', '', '', 'big'));
		$fieldset->add_element(new FormButtonButton('.bigger', '', '', 'bigger'));
		$fieldset->add_element(new FormButtonButton('.biggest', '', '', 'biggest'));

		$fieldset->add_field(new FormFieldFree('button_colors', '', ''));
		$fieldset->add_element(new FormButtonButton('.error', '', '', 'button error'));
		$fieldset->add_element(new FormButtonButton('.error.bgc', '', '', 'button bgc error'));
		$fieldset->add_element(new FormButtonButton('.warning', '', '', 'button warning'));
		$fieldset->add_element(new FormButtonButton('.warning.bgc', '', '', 'button bgc warning'));
		$fieldset->add_element(new FormButtonButton('.success', '', '', 'button success'));
		$fieldset->add_element(new FormButtonButton('.success.bgc', '', '', 'button bgc success'));
		$fieldset->add_element(new FormButtonButton('.question', '', '', 'button question'));
		$fieldset->add_element(new FormButtonButton('.question.bgc', '', '', 'button bgc question'));
		$fieldset->add_element(new FormButtonButton('.notice', '', '', 'button notice'));
		$fieldset->add_element(new FormButtonButton('.notice.bgc', '', '', 'button bgc notice'));
		$fieldset->add_element(new FormButtonButton('.member', '', '', 'button member'));
		$fieldset->add_element(new FormButtonButton('.member.bgc', '', '', 'button bgc member'));
		$fieldset->add_element(new FormButtonButton('.moderator', '', '', 'button moderator'));
		$fieldset->add_element(new FormButtonButton('.moderator.bgc', '', '', 'button bgc moderator'));
		$fieldset->add_element(new FormButtonButton('.administrator', '', '', 'button administrator'));
		$fieldset->add_element(new FormButtonButton('.administrator.bgc', '', '', 'button bgc administrator'));


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
			array('required' => true, 'class' => 'form-field-css-class')
		));

		// COLOR PICKER
		$fieldset2->add_field(new FormFieldColorPicker('color', $this->lang['form.color'], '#366393',
			array('class' => 'form-field-css-class')
		));

		// SEARCH
		$fieldset2->add_field(new FormFieldSearch('search', $this->lang['form.search'], '',
			array('class' => 'form-field-css-class')
		));

		// FILE PICKER
		$fieldset2->add_field(new FormFieldFilePicker('file', $this->lang['form.file.picker'],
			array('class' => 'form-field-css-class')
		));

		// MULTIPLE FILE PICKER
		$fieldset2->add_field(new FormFieldFilePicker('multiple_files', $this->lang['form.multiple.file.picker'],
			array('class' => 'form-field-css-class', 'multiple' => true)
		));

		// UPLOAD FILE
		$fieldset2->add_field(new FormFieldUploadFile('upload_file', $this->lang['form.file.upload'], '',
			array('required' => true, 'class' => 'form-field-css-class')
		));

		// List actionLinks
		$fieldset2->add_field(new FormFieldActionLinkList('actionlink_list',
			array(
				new FormFieldActionLinkElement($this->lang['form.action.link.1'], '#', ''),
				new FormFieldActionLinkElement($this->lang['form.action.link.2'], '#', ''),
				new FormFieldActionLinkElement($this->lang['form.action.link.3'], '#', ''),
				new FormFieldActionLinkElement($this->lang['form.action.link.4'], '#', '')
			),
			array('description'=> $this->lang['form.action.link.list'], 'class' => 'form-field-css-class')
		));

		// GOOGLE MAPS
		if (ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key())
		{
			$fieldset_maps = new FormFieldsetHTML('fieldset_maps', $this->lang['form.googlemap']);
			$form->add_fieldset($fieldset_maps);

			// SIMPLE ADDRESS
			$fieldset_maps->add_field(new GoogleMapsFormFieldSimpleAddress('simple_address', $this->lang['form.googlemap.simple_address'], '', array('class' => 'form-field-css-class')));

			// MAP ADDRESS
			$fieldset_maps->add_field(new GoogleMapsFormFieldMapAddress('map_address', $this->lang['form.googlemap.map_address'], '', array('class' => 'form-field-css-class', 'include_api' => false)));

			// SIMPLE MARKER
			$fieldset_maps->add_field(new GoogleMapsFormFieldSimpleMarker('simple_marker', $this->lang['form.googlemap.simple_marker'], '', array('class' => 'form-field-css-class', 'include_api' => false)));

			// MULTIPLE MARKERS
			$fieldset_maps->add_field(new GoogleMapsFormFieldMultipleMarkers('multiple_markers', $this->lang['form.googlemap.multiple_markers'], '', array('class' => 'form-field-css-class', 'include_api' => false)));
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
		$buttons_fieldset->add_element(new FormButtonButton($this->lang['form.button'], 'alert("Hello world");', '', 'button'));
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

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->common_lang['title.form.builder'], $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.form.builder'], SandboxUrlBuilder::form()->rel());

		return $response;
	}
}
?>
