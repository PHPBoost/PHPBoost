<?php
/*##################################################
 *                             form_builer.class.php
 *                            -------------------
 *   begin                : April 28, 2009
 *   copyright            : (C) 2009 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

/**
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class allow you to manage easily the forms in your modules. 
 * A lot a sort of field and options are supported, for further details refer to each field type classes.
 * 
 * Example of use :
 * import('builder/forms/form_builder');
	$form = new FormBuilder('test', 'Test form');
	$form->add_field('login', 'text', array('title' => 'Login', 'subtitle' => 'Enter your login', 'class' => 'text', 'required' => true));
	//Textarea field
	$form->add_field('contents', 'textarea', array('title' => 'Description', 'subtitle' => 'Enter a description', 'rows' => 10, 'cols' => 10, 'required' => true));
	$form->add_field('comments', 'textarea', array('title' => 'Comments', 'subtitle' => '', 'rows' => 4, 'cols' => 5, 'editor' => false));
	$form->displayPreview('contents'); //Display a preview button for the textarea field(ajax).
	//Radio button field
	$form->add_field('choice', 'radio', array('title' => 'Answer', 'optiontitle' => 'Choix1', 'value' => 1, 'checked' => 0));
	$form->add_field('choice', 'radio', array('optiontitle' => 'Choix2', 'value' => 2, 'checked' => 1));
	//Checkbox button field
	$form->add_field('multiplechoice', 'checkbox', array('title' => 'Answer2', 'optiontitle' => 'Choix3', 'value' => 1));
	$form->add_field('multiplechoice', 'checkbox', array('optiontitle' => 'Choix4', 'value' => 2, 'checked' => 1));
	//Select field
	$form->add_field('sex', 'select', array('title' => 'Sex', 'optiontitle' => 'Men', 'value' => 1));
	$form->add_field('sex', 'select', array('optiontitle' => 'Women', 'value' => 1));
	$form->add_field('sex', 'select', array('optiontitle' => '?', 'value' => -1, 'selected' => 1));
	//File field
	$form->add_field('avatar', 'file', array('title' => 'Avatar', 'subtitle' => 'Upload a file', 'class' => 'file', 'size' => 30, 'required' => true));
	//Radio button field
	$form->add_field('test', 'hidden', array('value' => 1));
	
	echo $form->display();
 * @package builder
 */ 

define('FIELD_INPUT__TEXT', 'text');
define('FIELD_INPUT__RADIO', 'radio');
define('FIELD_INPUT__CHECKBOX', 'checkbox');
define('FIELD_INPUT__HIDDEN', 'hidden');
define('FIELD_INPUT__FILE', 'file');
define('FIELD__TEXTAREA', 'textarea');
define('FIELD__SELECT', 'select');

class FormBuilder
{
	/**
	 * @desc constructor
	 * @param $form_name The name of the form.
	 * @param $form_title The tite displayed for the form.
	 * @param $form_action The url where the form send the data.
	 */
	function FormBuilder($form_name, $form_title = '', $form_action = '')
	{
		global $LANG;
		
		$this->form_name = $form_name;
		$this->form_title = $form_title;
		$this->form_action = $form_action;
		$this->form_submit = $LANG['submit'];
	}
	
	/**
	 * @desc Constuct the fields objects and store them in the form.
	 * @param $field_name
	 * @param $fieldType
	 * @param $arrayOptions
	 * @return unknown_type
	 */
	function add_field($field_name, $fieldType, $arrayOptions = array())
	{
		switch ($fieldType)
		{
			case FIELD_INPUT__TEXT :
				import('builder/forms/field_input_text');
				$this->form_fields[$field_name] = new FormInputText($field_name, $arrayOptions);
			break;
			case FIELD_INPUT__HIDDEN :
				import('builder/forms/field_input_hidden');
				$this->form_fields[$field_name] = new FormInputHidden($field_name, $arrayOptions);
			break;
			case FIELD_INPUT__FILE :
				import('builder/forms/field_input_file');
				$this->form_fields[$field_name] = new FormInputFile($field_name, $arrayOptions);
			break;
			case FIELD__TEXTAREA :
				import('builder/forms/field_textarea');
				$this->form_fields[$field_name] = new FormTextarea($field_name, $arrayOptions);
			break;
			case FIELD_INPUT__RADIO :
				import('builder/forms/field_input_radio');
				if (isset($this->form_fields[$field_name]))
				{	
					$tmpField = new FormInputRadio($field_name, $arrayOptions);
					$this->form_fields[$field_name]->add_option($tmpField);
				}
				else
				{
					$this->form_fields[$field_name] = new FormInputRadio($field_name, $arrayOptions);
					$this->form_fields[$field_name]->add_option($this->form_fields[$field_name]);
				}
			break;
			case FIELD_INPUT__CHECKBOX :
				import('builder/forms/field_input_checkbox');
				if (isset($this->form_fields[$field_name]))
				{	
					$tmpField = new FormInputCheckbox($field_name, $arrayOptions);
					$this->form_fields[$field_name]->add_option($tmpField);
				}
				else
				{
					$this->form_fields[$field_name] = new FormInputCheckbox($field_name, $arrayOptions);
					$this->form_fields[$field_name]->add_option($this->form_fields[$field_name]);
				}
			break;
			case FIELD__SELECT :
				import('builder/forms/field_select');
				if (isset($this->form_fields[$field_name]))
				{	
					$tmpField = new FormSelect($field_name, $arrayOptions);
					$this->form_fields[$field_name]->add_option($tmpField);
				}
				else
				{
					$this->form_fields[$field_name] = new FormSelect($field_name, $arrayOptions);
					$this->form_fields[$field_name]->add_option($this->form_fields[$field_name]);
				}
			break;
		}
	}

	/**
	 * @desc Return the form
	 * @param $Template Optionnal template
	 * @return string
	 */
	function display($Template = false)
	{
		global $LANG;
		
		if (!is_object($Template) || strtolower(get_class($Template)) != 'template')
			$Template = new Template('framework/builder/forms/forms.tpl');
			
		$Template->assign_vars(array(
			'C_DISPLAY_PREVIEW' => $this->display_preview,
			'C_DISPLAY_RESET' => $this->display_reset, 
			'FORMONSUBMIT' => $this->form_on_submit,
			'FORMCLASS' => $this->form_class,
			'U_FORMACTION' => $this->form_action,
			'L_FORMTITLE' => $this->form_title,
			'L_FORMNAME' => $this->form_name,
			'L_FIELD_CONTENT_PREVIEW' => $this->field_content_preview,
			'L_REQUIRED_FIELDS' => $LANG['require'],
			'L_SUBMIT' => $this->form_submit,
			'L_PREVIEW' => $LANG['preview'],
			'L_RESET' => $LANG['reset'],
		));	
		
		foreach($this->form_fields as $Field)
		{
			$Template->assign_block_vars('fields', array(
				'FIELD' => $Field->display(),
			));	
		}
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}
	
	/**
	 * @desc Display the preview button for textarea fields.
	 * @param $field_content_preview The identifier of the textarea.
	 * @param $value True to display, false to hide.
	 */
	function displayPreview($field_content_preview, $value = true)
	{
		$this->field_content_preview = $field_content_preview;
		$this->display_preview = true;
	}
	/**
	 * @desc Display a reset button for the form.
	 * @param $value True to display, false to hide.
	 */
	function displayReset($value)
	{
		$this->display_reset = $value;
	}
	
	//Setteurs
	function set_form_title($form_title) { $this->form_title = $form_title; }
	function set_form_name($form_name) { $this->form_name = $form_name; }
	function set_form_submit($form_submit) { $this->form_submit = $form_submit; }
	function set_form_action($form_action) { $this->form_action = $form_action; }
	function set_form_onsubmit($form_on_submit) { $this->form_on_submit = $form_on_submit; }
	function set_form_class($form_class) { $this->form_class = $form_class; }
	
	//Getteurs
	function get_form_title() { return $this->form_title; }
	function get_form_name() { return $this->form_name; }
	function get_form_submit() { return $this->form_submit; }
	function get_form_action() { return $this->form_action; }
	function get_form_onsubmit() { return $this->form_on_submit; }
	function get_form_class() { return $this->form_class; }
	
	var $form_fields = array(); //Fields stored
	var $form_name = '';
	var $form_title = '';
	var $form_submit = '';
	var $form_action = '';
	var $form_on_submit = ''; //Action performed on submit (javascript).
	var $form_class = 'fieldset_mini';
	var $field_content_preview = 'contents'; //Field identifier of textarea for preview.

	var $display_preview = false;
	var $display_reset = true;
}

?>