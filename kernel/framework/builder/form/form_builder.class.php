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
 * @desc This class allows you to manage easily the forms in your modules. 
 * A lot of fields and options are supported, for further details refer yourself to each field class.
 * 
 * Example of use :
 *<code>
*	import('builder/form/form_builder');
*	$form = new FormBuilder('test', '');
*	
*	//####### First fieldset ######//
*	$fieldset = new FormFieldset('Form Test');
*	
*	$fieldset->add_field(new FormTextEdit('login', array('title' => 'Login', 'subtitle' => 'Enter your login', 'class' => 'text', 'required' => true, 'required_alert' => 'Login field has to be filled')));
*	//Textarea field
*	$fieldset->add_field(new FormTextarea('contents', array('title' => 'Description', 'subtitle' => 'Enter a description', 'rows' => 10, 'cols' => 10, 'required' => true, 'required_alert' => 'Content field has to be filled')));
*	$fieldset->add_field(new FormTextarea('comments', array('title' => 'Comments', 'subtitle' => '', 'rows' => 4, 'cols' => 5, 'editor' => false)));
*	//Radio button field
*	$fieldset->add_field(new FormRadioChoice('choice', array('title' => 'Answer'),
*		new FormRadioChoiceOption(array('optiontitle' => 'Choix1', 'value' => 1)), 
*		new FormRadioChoiceOption(array('optiontitle' => 'Choix2', 'value' => 2, 'checked' => true))
*	));
*	
*	//Checkbox button field
*	$fieldset->add_field(new FormCheckbox('multiplechoice', array('title' => 'Answer2'),
*		new FormCheckboxOption(array('optiontitle' => 'Choix3', 'value' => 1)), 
*		new FormCheckboxOption(array('optiontitle' => 'Choix4', 'value' => 2, 'checked' => true)) 
*	));
*	//Select field
*	$fieldset->add_field(new FormSelect('sex', array('title' => 'Sex'),
*		new FormSelectOption(array('optiontitle' => 'Men', 'value' => 1)), 
*		new FormSelectOption(array('optiontitle' => 'Women', 'value' => 2)), 
*		new FormSelectOption(array('optiontitle' => '?', 'value' => -1, 'selected' => true))
*	));
*	
*	$form->add_fieldset($fieldset);  //Add fieldset to the form.
*	
*	//####### Second fieldset #######//
*	$fieldset_up = new FormFieldset('Upload file');
*	//File field
*	$fieldset_up->add_field(new FormFileUploader('avatar', array('title' => 'Avatar', 'subtitle' => 'Upload a file', 'class' => 'file', 'size' => 30)));
*	//Radio button field
*	$fieldset_up->add_field(new FormHiddenField('test', array('value' => 1)));
*	
*	$form->add_fieldset($fieldset_up);  //Add fieldset to the form.
*	
*	$form->display_preview_button('contents'); //Display a preview button for the textarea field(ajax).
*	echo $form->display(); //Display form.
*</code>
 * @package builder
 * @subpackage form
 */ 

define('FIELD_INPUT__TEXT', 'text');
define('FIELD_INPUT__RADIO', 'radio');
define('FIELD_INPUT__CHECKBOX', 'checkbox');
define('FIELD_INPUT__HIDDEN', 'hidden');
define('FIELD_INPUT__FILE', 'file');
define('FIELD__TEXTAREA', 'textarea');
define('FIELD__SELECT', 'select');

import('builder/form/form_fieldset');
import('builder/form/form_text_edit');
import('builder/form/form_hidden_field');
import('builder/form/form_file_uploader');
import('builder/form/form_textarea');
import('builder/form/form_radio_choice');
import('builder/form/form_checkbox');
import('builder/form/form_select');

class FormBuilder
{
	/**
	 * @desc constructor
	 * @param string $form_name The name of the form.
	 * @param string $form_title The tite displayed for the form.
	 * @param string $form_action The url where the form send the data.
	 */
	function FormBuilder($form_name, $form_action = '')
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
	function add_fieldset($fieldset)
	{
		$this->form_fieldsets[] = $fieldset;
	}
	
	/**
	 * @desc Return the form
	 * @param Template $Template Optionnal template
	 * @return string
	 */
	function display($Template = false)
	{
		global $LANG;
		
		if (!is_object($Template) || strtolower(get_class($Template)) != 'template')
			$Template = new Template('framework/builder/forms/form.tpl');
			
		$Template->assign_vars(array(
			'C_DISPLAY_PREVIEW' => $this->display_preview,
			'C_DISPLAY_RESET' => $this->display_reset, 
			'FORMCLASS' => $this->form_class,
			'U_FORMACTION' => $this->form_action,
			'L_FORMNAME' => $this->form_name,
			'L_FIELD_CONTENT_PREVIEW' => $this->field_identifier_preview,
			'L_SUBMIT' => $this->form_submit,
			'L_PREVIEW' => $LANG['preview'],
			'L_RESET' => $LANG['reset'],
		));	
		
		foreach($this->form_fieldsets as $Fieldset)
		{
			foreach($Fieldset->get_fields() as $Field)
			{
				$field_required_alert = $Field->get_required_alert();
				if (!empty($field_required_alert))
				{
					$Template->assign_block_vars('check_form', array(
						'FIELD_ID' => $Field->get_id(),
						'FIELD_REQUIRED_ALERT' => str_replace('"', '\"', $field_required_alert)
					));
				}
			}
			
			$Template->assign_block_vars('fieldsets', array(
				'FIELDSET' => $Fieldset->display(),
			));	
		}
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}
	
	/**
	 * @desc Display a preview button for the specified field.
	 * @param string $fieldset_title The fieldset title
	 */
	function display_preview_button($field_identifier_preview) 
	{ 
		$this->display_preview = true;
		$this->field_identifier_preview = $field_identifier_preview; 
	}
	
	/**
	 * @desc Display a reset button for the form.
	 * @param boolean $value True to display, false to hide.
	 */
	function display_reset($value)
	{
		$this->display_reset = $value;
	}
	
	//Setteurs
	function set_form_name($form_name) { $this->form_name = $form_name; }
	function set_form_submit($form_submit) { $this->form_submit = $form_submit; }
	function set_form_action($form_action) { $this->form_action = $form_action; }
	function set_form_class($form_class) { $this->form_class = $form_class; }
	
	//Getteurs
	function get_form_name() { return $this->form_name; }
	function get_form_submit() { return $this->form_submit; }
	function get_form_action() { return $this->form_action; }
	function get_form_class() { return $this->form_class; }

	var $form_fieldsets = array(); //Fieldsets stored
	var $form_name = '';
	var $form_submit = '';
	var $form_action = '';
	var $form_class = 'fieldset_mini';
	var $display_preview = false; //Field identifier of textarea for preview.
	var $field_identifier_preview = 'contents'; //Field identifier of textarea for preview.

	var $display_reset = true;
}

?>