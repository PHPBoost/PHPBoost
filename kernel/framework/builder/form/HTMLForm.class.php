<?php
/*##################################################
 *                             Form.class.php
 *                            -------------------
 *   begin                : April 28, 2009
 *   copyright            : (C) 2009 Viarre Régis
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
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class allows you to manage easily the forms in your modules.
 * A lot of fields and options are supported, for further details refer yourself to each field class.
 *
 * Example of use :
 *<code>
 *
 *	$form = new FormBuilder('test', '');
 *
 *	//####### First fieldset ######//
 *	$fieldset = new FormFieldsetHTML('Form Test');
 *
 *	$fieldset->add_field(new FormFieldTextEditor('login', 'Default value', array('title' => 'Login', 'subtitle' => 'Enter your login', 'class' => 'text', 'required' => true, 'required_alert' => 'Login field has to be filled')));
 *	//Textarea field
 *	$fieldset->add_field(new FormTextarea('contents', '', array('title' => 'Description', 'subtitle' => 'Enter a description', 'rows' => 10, 'cols' => 10, 'required' => true, 'required_alert' => 'Content field has to be filled')));
 *	$fieldset->add_field(new FormTextarea('comments', '', array('title' => 'Comments', 'subtitle' => '', 'rows' => 4, 'cols' => 5, 'editor' => false)));
 *	//Radio button field
 *	$fieldset->add_field(new FormFieldRadio('choice', array('title' => 'Answer'),
 *		new FormFieldRadioOption('Choix1', 1),
 *		new FormFieldRadioOption('Choix2', 2, FormFieldRadioOption::CHECKED)
 *	));
 *
 *	//Checkbox button field
 *	$fieldset->add_field(new FormFieldCheckbox('multiplechoice', array('title' => 'Answer2'),
 *		new FormFieldCheckboxOption('Choix3', 1),
 *		new FormFieldCheckboxOption('Choix4', 2, FormFieldCheckboxOption::CHECKED)
 *	));
 *	//Select field
 *	$fieldset->add_field(new FormFieldSelect('sex', array('title' => 'Sex'),
 *		new FormFieldSelectOption('Men', 1),
 *		new FormFieldSelectOption('Women', 2),
 *		new FormFieldSelectOption('?', -1, FormFieldSelectOption::SELECTED)
 *	));
 *
 *	$form->add_fieldset($fieldset);  //Add fieldset to the form.
 *
 *	//####### Second fieldset #######//
 *	$fieldset_up = new FormFieldsetHTML('Upload file');
 *	//File field
 *	$fieldset_up->add_field(new FormFieldFilePicker('avatar', array('title' => 'Avatar', 'subtitle' => 'Upload a file', 'class' => 'file', 'size' => 30)));
 *	//Radio button field
 *	$fieldset_up->add_field(new FormFieldHidden('test', 1));
 *
 *	//Captcha
 *
 *	$captcha = new Captcha();
 *	$fieldset->add_field(new FormFieldCaptcha('verif_code', $captcha));
 *
 *	$form->add_fieldset($fieldset_up);  //Add fieldset to the form.
 *
 *	$form->display_preview_button('contents'); //Display a preview button for the textarea field(ajax).
 *	echo $form->display(); //Display form.
 *</code>
 * @package builder
 * @subpackage form
 */
class HTMLForm
{
	const METHOD_POST = 'post';
	const METHOD_GET = 'get';

	const SMALL_CSS_CLASS = 'fieldset_mini';
	const NORMAL_CSS_CLASS = 'fieldset_content';

	private $constraints = array();
	private $fieldsets = array();
	private $buttons = array();
	private $html_id = '';
	private $target = '';
	private $css_class = self::NORMAL_CSS_CLASS;
	private static $js_already_included = false;

	private $validation_error_messages = array();

	/**
	 * @desc constructor
	 * @param string $html_id The name of the form.
	 * @param string $form_title The tite displayed for the form.
	 * @param string $target The url where the form sends data.
	 */
	public function __construct($html_id, $target = '')
	{
		$this->set_html_id($html_id);
		$this->set_target($target);
	}

	/**
	 * @desc Add fieldset in the form.
	 * @param FormFieldset The fieldset object.
	 */
	public function add_fieldset(FormFieldset $fieldset)
	{
		$this->fieldsets[] = $fieldset;
	}

	public function validate()
	{
		$validation_result = true;

		foreach ($this->fieldsets as $fieldset)
		{
			if (!$fieldset->validate())
			{
				$validation_error_message = $fieldset->get_validation_error_messages();
				if (!empty($validation_error_message))
				{
					$this->validation_error_messages = array_merge($this->validation_error_messages, $validation_error_message);
				}
				$validation_result = false;
			}
		}
		foreach ($this->constraints as $constraint)
		{
			if (!$constraint->validate())
			{
				$validation_result = false;
			}
		}
		if (!$validation_result)
		{
			$this->validation_error_messages[] = LangLoader::get_message('validation_error', 'builder-form-Validator');
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
		$field = $this->get_field_by_id($field_id);
		return $field->get_value();
	}

	/**
	 * @param string $field_id
	 * @return FormField
	 * @throws FormBuilderException
	 */
	private function get_field_by_id($field_id)
	{
		foreach ($this->fieldsets as $fieldset)
		{
			if ($fieldset->has_field($field_id))
			{
				return $fieldset->get_field($field_id);
			}
		}
		throw new FormBuilderException('The field "' . $field_id .
			'" doesn\'t exists in the "' . $this->html_id . '" form');
	}

	/**
	 * @desc Return the form
	 * @param Template $Template Optionnal template
	 * @return string
	 */
	public function display()
	{
		global $LANG;

		$template = new FileTemplate('framework/builder/form/Form.tpl');

		$template->assign_vars(array(
			'C_JS_NOT_ALREADY_INCLUDED' => !self::$js_already_included,
			// TODO can be removed?
			'C_BBCODE_TINYMCE_MODE' => AppContext::get_user()->get_attribute('user_editor') == 'tinymce',
			'C_HAS_REQUIRED_FIELDS' => $this->has_required_fields(),
			'FORMCLASS' => $this->css_class,
			'U_FORMACTION' => $this->target,
			'L_FORMNAME' => $this->html_id,
			'L_REQUIRED_FIELDS' => $LANG['require'],
			'C_VALIDATION_ERROR' => count($this->validation_error_messages)
		));

		foreach ($this->validation_error_messages as $error_message)
		{
			$template->assign_block_vars('validation_error_messages', array(
				'ERROR_MESSAGE' => $error_message
			));
		}

		self::$js_already_included = true;

		foreach ($this->fieldsets as $fieldset)
		{
			$template->assign_block_vars('fieldsets', array(), array(
				'FIELDSET' => $fieldset->display()
			));

			//Onsubmit constraints
			foreach ($fieldset->get_onsubmit_validations() as $constraints)
			{
				foreach ($constraints as $constraint)
				{
					$template->assign_block_vars('check_constraints', array(
						'ONSUBMIT_CONSTRAINTS' => $constraint
					));
				}
			}
		}

		foreach ($this->buttons as $button)
		{
			$template->assign_block_vars('buttons', array(), array(
				'BUTTON' => $button->display() 
			));
		}

		return $template;
	}

	public function set_html_id($html_id)
	{
		$this->html_id = $html_id;
	}

	public function set_target($target)
	{
		$this->target = $target;
	}

	public function set_css_class($css_class)
	{
		$this->css_class = $css_class;
	}

	private function has_required_fields()
	{
		foreach ($this->fieldsets as $fieldset)
		{
			foreach($fieldset->get_fields() as $field)
			{
				if ($field->is_required())
				{
					return true;
				}
			}
		}
		return false;
	}

	public function add_button(FormButton $button)
	{
		$this->buttons[] = $button;
	}

	// TODO add automatic CSRF protection
}

?>