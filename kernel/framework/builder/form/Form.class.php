<?php
/*##################################################
 *                             Form.class.php
 *                            -------------------
 *   begin                : April 28, 2009
 *   copyright            : (C) 2009 Viarre R�gis
 *   email                : crowkait@phpboost.com
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

/**
 * @author R�gis Viarre <crowkait@phpboost.com>
 * @desc This class allows you to manage easily the forms in your modules.
 * A lot of fields and options are supported, for further details refer yourself to each field class.
 *
 * Example of use :
 *<code>
 *
 *	$form = new FormBuilder('test', '');
 *
 *	//####### First fieldset ######//
 *	$fieldset = new FormFieldset('Form Test');
 *
 *	$fieldset->add_field(new FormTextEdit('login', 'Default value', array('title' => 'Login', 'subtitle' => 'Enter your login', 'class' => 'text', 'required' => true, 'required_alert' => 'Login field has to be filled')));
 *	//Textarea field
 *	$fieldset->add_field(new FormTextarea('contents', '', array('title' => 'Description', 'subtitle' => 'Enter a description', 'rows' => 10, 'cols' => 10, 'required' => true, 'required_alert' => 'Content field has to be filled')));
 *	$fieldset->add_field(new FormTextarea('comments', '', array('title' => 'Comments', 'subtitle' => '', 'rows' => 4, 'cols' => 5, 'editor' => false)));
 *	//Radio button field
 *	$fieldset->add_field(new FormRadioChoice('choice', array('title' => 'Answer'),
 *		new FormRadioChoiceOption('Choix1', 1),
 *		new FormRadioChoiceOption('Choix2', 2, FormRadioChoiceOption::CHECKED)
 *	));
 *
 *	//Checkbox button field
 *	$fieldset->add_field(new FormCheckbox('multiplechoice', array('title' => 'Answer2'),
 *		new FormCheckboxOption('Choix3', 1),
 *		new FormCheckboxOption('Choix4', 2, FormCheckboxOption::CHECKED)
 *	));
 *	//Select field
 *	$fieldset->add_field(new FormSelect('sex', array('title' => 'Sex'),
 *		new FormSelectOption('Men', 1),
 *		new FormSelectOption('Women', 2),
 *		new FormSelectOption('?', -1, FormSelectOption::SELECTED)
 *	));
 *
 *	$form->add_fieldset($fieldset);  //Add fieldset to the form.
 *
 *	//####### Second fieldset #######//
 *	$fieldset_up = new FormFieldset('Upload file');
 *	//File field
 *	$fieldset_up->add_field(new FormFileUploader('avatar', array('title' => 'Avatar', 'subtitle' => 'Upload a file', 'class' => 'file', 'size' => 30)));
 *	//Radio button field
 *	$fieldset_up->add_field(new FormHiddenField('test', 1));
 *
 *	//Captcha
 *
 *	$captcha = new Captcha();
 *	$fieldset->add_field(new FormCaptchaField('verif_code', $captcha));
 *
 *	$form->add_fieldset($fieldset_up);  //Add fieldset to the form.
 *
 *	$form->display_preview_button('contents'); //Display a preview button for the textarea field(ajax).
 *	echo $form->display(); //Display form.
 *</code>
 * @package builder
 * @subpackage form
 */
class Form implements ValidableFormComponent
{
	const METHOD_POST = 'post';
	const METHOD_GET = 'get';

	private $constraints = array();
	private $form_fieldsets = array(); //Fieldsets stored
	private $form_name = '';
	private $form_submit = '';
	private $form_action = '';
	private $form_class = 'fieldset_mini';
	private $display_preview = false; //Field identifier of textarea for preview.
	private $field_identifier_preview = 'contents'; //Field identifier of textarea for preview.
	private $display_reset = true;
	private $validation_error_message = '';

	private static $js_already_included = false;

	/**
	 * @desc constructor
	 * @param string $form_name The name of the form.
	 * @param string $form_title The tite displayed for the form.
	 * @param string $form_action The url where the form send the data.
	 */
	public function __construct($form_name, $form_action = '')
	{
		global $LANG;

		$this->form_name = $form_name;
		$this->form_action = $form_action;
		$this->form_submit = $LANG['submit'];
	}

	/**
	 * @desc Add fieldset in the form.
	 * @param FormFieldset The fieldset object.
	 */
	public function add_fieldset(FormFieldset $fieldset)
	{
		$fieldset->set_form_name($this->form_name);
		$this->form_fieldsets[] = $fieldset;
	}

	public function validate()
	{
		$validation_result = true;

		foreach ($this->form_fieldsets as $fieldset)
		{
			if (!$fieldset->validate())
			{
				$validation_result = false;;
			}
		}
		foreach ($this->constraints as $constraint)
		{
			if (!$constraint->validate($this))
			{
				$validation_result = false;;
			}
		}
		if (!$validation_result)
		{
			$this->validation_error_message = LangLoader::get_message('validation_error', 'builder-form-Validator');
		}
		return $validation_result;
	}

	public function add_constraint(FormConstraint $constraint)
	{
		$this->constraints[] = $constraint;
	}

	/**
	 * @param string $field_id
	 * @return mixed
	 * @throws FormBuilderException
	 */
	public function get_value($field_id)
	{
		$field = $this->get_element_by_id($field_id);
		return $field->get_value();
	}

	/**
	 * @param string $field_id
	 * @return FormField
	 * @throws FormBuilderException
	 */
	private function get_field_by_id($field_id)
	{
		foreach ($this->form_fieldsets as $fieldset)
		{
			if ($fieldset->has_field($field_id))
			{
				return $fieldset->get_field($field_id);
			}
		}
		throw new FormBuilderException('The field "' . $field_id .
			'" doesn\'t exists in the "' . $this->form_name . '" form');
	}

	/**
	 * @desc Return the form
	 * @param Template $Template Optionnal template
	 * @return string
	 */
	public function export($template = false)
	{
		global $LANG;

		if (!is_object($template) || strtolower(get_class($template)) != 'template')
		{
			$template = new Template('framework/builder/forms/form.tpl');
		}
			
		$template->assign_vars(array(
			'C_JS_NOT_ALREADY_INCLUDED' => !self::$js_already_included,
			'C_DISPLAY_PREVIEW' => $this->display_preview,
			'C_DISPLAY_RESET' => $this->display_reset, 
			'C_BBCODE_TINYMCE_MODE' => AppContext::get_user()->get_attribute('user_editor') == 'tinymce',
			'FORMCLASS' => $this->form_class,
			'U_FORMACTION' => $this->form_action,
			'L_FORMNAME' => $this->form_name,
			'L_FIELD_CONTENT_PREVIEW' => $this->field_identifier_preview,
			'L_SUBMIT' => $this->form_submit,
			'L_PREVIEW' => $LANG['preview'],
			'L_RESET' => $LANG['reset'],
			'C_VALIDATION_ERROR' => !empty($this->validation_error_message),
			'VALIDATION_ERROR' => $this->validation_error_message,
		));
		self::$js_already_included = true;

		$i = 0;
		foreach($this->form_fieldsets as $fieldset)
		{
			$template->assign_block_vars('fieldsets', array(
				'FIELDSET' => $fieldset->display()
			));
				
			foreach($fieldset->get_onsubmit_validations() as $constraints)
			{
				foreach($constraints as $constraint)
				{
					$template->assign_block_vars('check_constraints', array(
						'ONSUBMIT_CONSTRAINTS' => $constraint
					));
				}
			}
		}

		return $template;
	}

	/**
	 * @desc Display a preview button for the specified field.
	 * @param string $fieldset_title The fieldset title
	 */
	public function display_preview_button($field_identifier_preview)
	{
		$this->display_preview = true;
		$this->field_identifier_preview = $field_identifier_preview;
	}

	/**
	 * @desc Display a reset button for the form.
	 * @param boolean $value True to display, false to hide.
	 */
	public function display_reset($value)
	{
		$this->display_reset = $value;
	}

	//Setteurs
	public function set_form_name($form_name) { $this->form_name = $form_name; }
	public function set_form_submit($form_submit) { $this->form_submit = $form_submit; }
	public function set_form_action($form_action) { $this->form_action = $form_action; }
	public function set_form_class($form_class) { $this->form_class = $form_class; }

	//Getteurs
	public function get_form_name() { return $this->form_name; }
	public function get_form_submit() { return $this->form_submit; }
	public function get_form_action() { return $this->form_action; }
	public function get_form_class() { return $this->form_class; }
}

?>