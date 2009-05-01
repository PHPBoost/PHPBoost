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
import('builder/forms/form_builder');
$form = new FormBuilder('test', '');

//####### First fieldset ######//
$fieldset = new FormFieldset('Test Form');

$fieldset->add_field(new FormInputText('login', array('title' => 'Login', 'subtitle' => 'Enter your login', 'class' => 'text', 'required' => true)));
//Textarea field
$fieldset->add_field(new FormTextarea('contents', array('title' => 'Description', 'subtitle' => 'Enter a description', 'rows' => 10, 'cols' => 10, 'required' => true)));
$fieldset->add_field(new FormTextarea('comments', array('title' => 'Comments', 'subtitle' => '', 'rows' => 4, 'cols' => 5, 'editor' => false)));
$fieldset->displayPreview('contents'); //Display a preview button for the textarea field(ajax).
//Radio button field
$fieldset->add_field(new FormInputRadio('choice', array('title' => 'Answer', 'optiontitle' => 'Choix1', 'value' => 1, 'checked' => 0)));
$fieldset->add_field(new FormInputRadio('choice', array('optiontitle' => 'Choix2', 'value' => 2, 'checked' => 1)));
//Checkbox button field
$fieldset->add_field(new FormInputCheckbox('multiplechoice', array('title' => 'Answer2', 'optiontitle' => 'Choix3', 'value' => 1)));
$fieldset->add_field(new FormInputCheckbox('multiplechoice', array('optiontitle' => 'Choix4', 'value' => 2, 'checked' => 1)));
//Select field
$fieldset->add_field(new FormSelect('sex', array('title' => 'Sex', 'optiontitle' => 'Men', 'value' => 1)));
$fieldset->add_field(new FormSelect('sex', array('optiontitle' => 'Women', 'value' => 1)));
$fieldset->add_field(new FormSelect('sex', array('optiontitle' => '?', 'value' => -1, 'selected' => 1)));

$form->add_fieldset($fieldset);  //Add fieldset to the form.

//####### Second fieldset #######//
$fieldset_up = new FormFieldset('Upload file');
//File field
$fieldset_up->add_field(new FormInputFile('avatar', array('title' => 'Avatar', 'subtitle' => 'Upload a file', 'class' => 'file', 'size' => 30, 'required' => true)));
//Radio button field
$fieldset_up->add_field(new FormInputHidden('test', array('value' => 1)));

$form->add_fieldset($fieldset_up);  //Add fieldset to the form.

echo $form->display(); //Display form.
 * @package builder
 */ 

define('FIELD_INPUT__TEXT', 'text');
define('FIELD_INPUT__RADIO', 'radio');
define('FIELD_INPUT__CHECKBOX', 'checkbox');
define('FIELD_INPUT__HIDDEN', 'hidden');
define('FIELD_INPUT__FILE', 'file');
define('FIELD__TEXTAREA', 'textarea');
define('FIELD__SELECT', 'select');

import('builder/forms/form_fieldset');
import('builder/forms/field_input_text');
import('builder/forms/field_input_hidden');
import('builder/forms/field_input_file');
import('builder/forms/field_textarea');
import('builder/forms/field_input_radio');
import('builder/forms/field_input_checkbox');
import('builder/forms/field_select');

class FormBuilder
{
	/**
	 * @desc constructor
	 * @param $form_name The name of the form.
	 * @param $form_title The tite displayed for the form.
	 * @param $form_action The url where the form send the data.
	 */
	function FormBuilder($form_name, $form_action = '')
	{
		global $LANG;
		
		$this->form_name = $form_name;
		$this->form_action = $form_action;
		$this->form_submit = $LANG['submit'];
	}
	
	/**
	 * @desc Add fieldset int the form.
	 * @param object The fieldset object.
	 */
	function add_fieldset($fieldset)
	{
		$this->form_fieldsets[] = $fieldset;
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
			'L_FORMNAME' => $this->form_name,
			'L_FIELD_CONTENT_PREVIEW' => $this->field_content_preview,
			'L_SUBMIT' => $this->form_submit,
			'L_PREVIEW' => $LANG['preview'],
			'L_RESET' => $LANG['reset'],
		));	
		
		foreach($this->form_fieldsets as $Fieldset)
		{
			$Template->assign_block_vars('fieldsets', array(
				'FIELDSET' => $Fieldset->display(),
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
	function set_form_name($form_name) { $this->form_name = $form_name; }
	function set_form_submit($form_submit) { $this->form_submit = $form_submit; }
	function set_form_action($form_action) { $this->form_action = $form_action; }
	function set_form_onsubmit($form_on_submit) { $this->form_on_submit = $form_on_submit; }
	function set_form_class($form_class) { $this->form_class = $form_class; }
	
	//Getteurs
	function get_form_name() { return $this->form_name; }
	function get_form_submit() { return $this->form_submit; }
	function get_form_action() { return $this->form_action; }
	function get_form_onsubmit() { return $this->form_on_submit; }
	function get_form_class() { return $this->form_class; }
	
	var $form_fieldsets = array(); //Fieldsets stored
	var $form_name = '';
	var $form_submit = '';
	var $form_action = '';
	var $form_on_submit = ''; //Action performed on submit (javascript).
	var $form_class = 'fieldset_mini';
	var $field_content_preview = 'contents'; //Field identifier of textarea for preview.

	var $display_preview = false;
	var $display_reset = true;
}

?>