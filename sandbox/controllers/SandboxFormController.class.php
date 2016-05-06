<?php
/*##################################################
 *                       SandboxFormController.class.php
 *                            -------------------
 *   begin                : December 20, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class SandboxFormController extends ModuleController
{
	private $view;
	private $lang;
	
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
		$this->view->add_lang($this->lang);
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxFormController.tpl');
		$this->view->add_lang($this->lang);
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
		$fieldset->add_field(new FormFieldTextEditor('text', $this->lang['form.input.text'], $this->lang['form.input.text.lorem'], array(
			'maxlength' => 25, 'description' => $this->lang['form.input.text.desc']),
			array(new FormFieldConstraintRegex('`^[a-z0-9_ ]+$`i'))
		));
		$fieldset->add_field(new FormFieldTextEditor('textdisabled', $this->lang['form.input.text.disabled'], '', array(
			'maxlength' => 25, 'description' => $this->lang['form.input.text.disabled.desc'], 'disabled' => true)
		));
		$fieldset->add_field(new FormFieldUrlEditor('siteweb', $this->lang['form.input.url'], $this->lang['form.input.url.placeholder'], array(
			'description' => $this->lang['form.input.url.desc'])
		));
		$fieldset->add_field(new FormFieldMailEditor('mail', $this->lang['form.input.email'], $this->lang['form.input.email.placeholder'], array(
			'description' => $this->lang['form.input.email.desc'])
		));
		$fieldset->add_field(new FormFieldMailEditor('mail_multiple', $this->lang['form.input.email.multiple'], $this->lang['form.input.email.multiple.placeholder'], array(
			'description' => $this->lang['form.input.email.multiple.desc'], 'multiple' => true)
		));
		$fieldset->add_field(new FormFieldTelEditor('tel', $this->lang['form.input.phone'], $this->lang['form.input.phone.placeholder'], array(
			'description' => $this->lang['form.input.phone.desc'])
		));
		$fieldset->add_field(new FormFieldTextEditor('text2', $this->lang['form.input.text.required'], $this->lang['form.input.text.lorem'], array(
			'maxlength' => 25, 'description' => $this->lang['form.input.text.required.filled'], 'required' => true)
		));
		$fieldset->add_field(new FormFieldTextEditor('text3', $this->lang['form.input.text.required'], '', array(
			'maxlength' => 25, 'description' => $this->lang['form.input.text.required.empty'], 'required' => true)
		));
		$fieldset->add_field(new FormFieldNumberEditor('age', $this->lang['form.input.number'], $this->lang['form.input.number.placeholder'], array(
			'min' => 10, 'max' => 100, 'description' => $this->lang['form.input.number.desc']),
			array(new FormFieldConstraintIntegerRange(10, 100))
		));
		$fieldset->add_field(new FormFieldDecimalNumberEditor('decimal', $this->lang['form.input.number.decimal'], $this->lang['form.input.number.decimal.placeholder'], array(
			'min' => 0, 'step' => 0.1, 'description' => $this->lang['form.input.number.decimal.desc'])
		));

		// RANGE
		$fieldset->add_field($password = new FormFieldRangeEditor('range', $this->lang['form.input.length'], $this->lang['form.input.length.placeholder'], array(
			'min' => 1, 'max' => 10, 'description' => $this->lang['form.input.length.desc'])
		));

		// PASSWORD
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['form.input.password'], $this->lang['form.input.password.placeholder'], array(
			'description' => $security_config->get_internal_password_min_length() . $this->lang['form.input.password.desc']),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()))
		));
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['form.input.password.confirm'], $this->lang['form.input.password.placeholder'], array(
			'description' => $security_config->get_internal_password_min_length() . $this->lang['form.input.password.desc']),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()))
		));
	   
		// SHORT MULTI LINE TEXT
		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('short_multi_line_text', $this->lang['form.input.multiline.medium'], $this->lang['form.input.multiline.lorem'],
			array('rows' => 3, 'required' => true)
		));
		
		// MULTI LINE TEXT
		$fieldset->add_field(new FormFieldMultiLineTextEditor('multi_line_text', $this->lang['form.input.multiline'], $this->lang['form.input.multiline.lorem'],
				array('rows' => 6, 'cols' => 47, 'description' => $this->lang['form.input.multiline.desc'], 'required' => true)
		));

		// RICH TEXT
		$fieldset->add_field(new FormFieldRichTextEditor('rich_text', $this->lang['form.input.rich.text'], $this->lang['form.input.rich.text.placeholder'],
			array('required' => true)
		));

		// CHECKBOX
		$fieldset->add_field(new FormFieldCheckbox('checkbox', $this->lang['form.input.checkbox'], FormFieldCheckbox::CHECKED));

		// MULTIPLE CHECKBOXES
		$fieldset->add_field(new FormFieldMultipleCheckbox('multiple_check_box', $this->lang['form.input.multiple.checkbox'], array('1'), 
			array(
				new FormFieldMultipleCheckboxOption('1', $this->lang['form.input.choice.1']), 
				new FormFieldMultipleCheckboxOption('2', $this->lang['form.input.choice.2'])
			),
			array('required' => true)
		));
		
		// RADIO
		$default_option = new FormFieldRadioChoiceOption($this->lang['form.input.choice.1'], '1');
		$fieldset->add_field(new FormFieldRadioChoice('radio', $this->lang['form.input.radio'], '',
			array(
				$default_option,
				new FormFieldRadioChoiceOption($this->lang['form.input.choice.2'], '2')
			),
			array('required' => true)
		));

		// SELECT
		$fieldset->add_field(new FormFieldSimpleSelectChoice('select', $this->lang['form.input.select'], '',
			array(
				new FormFieldSelectChoiceOption('', ''),
				new FormFieldSelectChoiceOption($this->lang['form.input.choice.1'], '1'),
				new FormFieldSelectChoiceOption($this->lang['form.input.choice.2'], '2'),
				new FormFieldSelectChoiceOption($this->lang['form.input.choice.3'], '3'),
				new FormFieldSelectChoiceGroupOption($this->lang['form.input.choice.group.1'], array(
					new FormFieldSelectChoiceOption($this->lang['form.input.choice.4'], '4'),
					new FormFieldSelectChoiceOption($this->lang['form.input.choice.5'], '5'),
				)),
				new FormFieldSelectChoiceGroupOption($this->lang['form.input.choice.group.2'], array(
					new FormFieldSelectChoiceOption($this->lang['form.input.choice.6'], '6'),
					new FormFieldSelectChoiceOption($this->lang['form.input.choice.7'], '7'),
				))
			),
			array('required' => true)
		));
		
		// SELECT MULTIPLE
		$fieldset->add_field(new FormFieldMultipleSelectChoice('multiple_select', $this->lang['form.input.multiple.select'], array('1', '2'),
			array(
				new FormFieldSelectChoiceOption($this->lang['form.input.choice.1'], '1'),
				new FormFieldSelectChoiceOption($this->lang['form.input.choice.2'], '2'),
				new FormFieldSelectChoiceOption($this->lang['form.input.choice.3'], '3')
			),
			array('required' => true)
		));
		
		$fieldset->add_field(new FormFieldTimezone('timezone', $this->lang['form.input.timezone'], 'UTC+0'));
		
		$fieldset->add_field(new FormFieldAjaxSearchUserAutoComplete('user_completition', $this->lang['form.input.user.completion'], ''));
		
		$fieldset->add_element(new FormButtonButton($this->lang['form.send.button']));

		$fieldset2 = new FormFieldsetHTML('fieldset2', $this->lang['form.title.2']);
		$form->add_fieldset($fieldset2);

		// CAPTCHA
		$fieldset2->add_field(new FormFieldCaptcha('Captcha'));

		// HIDDEN
		$fieldset2->add_field(new FormFieldHidden('hidden', $this->lang['form.input.hidden']));

		// FREE FIELD
		$fieldset2->add_field(new FormFieldFree('free', $this->lang['form.free.html'], $this->lang['form.input.text.lorem'], array()));

		// DATE
		$fieldset2->add_field(new FormFieldDate('date', $this->lang['form.date'], null,
			array('required' => true)
		));

		// DATE TIME
		$fieldset2->add_field(new FormFieldDateTime('date_time', $this->lang['form.date.hm'], null,
			array('required' => true)
		));

		// COLOR PICKER
		$fieldset2->add_field(new FormFieldColorPicker('color', $this->lang['form.color'], '#366393'));

		// SEARCH
		$fieldset2->add_field(new FormFieldSearch('search', $this->lang['form.search'], ''));
		
		// FILE PICKER
		$fieldset2->add_field(new FormFieldFilePicker('file', $this->lang['form.file.picker']));
		
		// MULTIPLE FILE PICKER
		$fieldset2->add_field(new FormFieldMultipleFilePicker('multiple_files', $this->lang['form.multiple.file.picker']));
		
		// UPLOAD FILE
		$fieldset2->add_field(new FormFieldUploadFile('upload_file', $this->lang['form.file.upload'], '', array('required' => true)));

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
		$vertical_fieldset->add_field(new FormFieldTextEditor('alone', $this->lang['form.input.text'], $this->lang['form.input.text.lorem']));
		$vertical_fieldset->add_field(new FormFieldCheckbox('cbhor', $this->lang['form.input.checkbox'], FormFieldCheckbox::UNCHECKED));

		// HORIZONTAL FIELDSET
		$horizontal_fieldset = new FormFieldsetHorizontal('fieldset5');
		$horizontal_fieldset->set_description($this->lang['form.horizontal.desc']);
		$form->add_fieldset($horizontal_fieldset);
		$horizontal_fieldset->add_field(new FormFieldTextEditor('texthor', $this->lang['form.input.text'], $this->lang['form.input.text.lorem'], array('required' => true)));
		$horizontal_fieldset->add_field(new FormFieldCheckbox('cbvert', $this->lang['form.input.checkbox'], FormFieldCheckbox::CHECKED));

		// BUTTONS
		$buttons_fieldset = new FormFieldsetSubmit('buttons');
		$buttons_fieldset->add_element(new FormButtonReset());
		$this->preview_button = new FormButtonSubmit($this->lang['form.preview'], 'preview', 'alert("Hello world preview")');
		$buttons_fieldset->add_element($this->preview_button);
		$this->submit_button = new FormButtonDefaultSubmit();
		$buttons_fieldset->add_element($this->submit_button);
		$buttons_fieldset->add_element(new FormButtonButton($this->lang['form.button'], 'alert("Hello world");'));
		$form->add_fieldset($buttons_fieldset);

		// FORM CONSTRAINTS
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));

		return $form;
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['title.form_builder'], $this->lang['module_title']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['title.form_builder'], SandboxUrlBuilder::form()->rel());
		
		return $response;
	}
}
?>
