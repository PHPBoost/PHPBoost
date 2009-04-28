<?php
/*##################################################
 *                             form_helper.class.php
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
 * @author Régis
 * @desc This class allows you to manage easily the forms in your modules. 
 * A lot a sort of field and options are supported, for further details refer to each field type classes.
 * 
 * Example of use :
 * import('helpers/forms/form_helper');
	$form = new FormHelper('test', 'Test formular');
	$form->addField('login', 'text', array('title' => 'Login', 'subtitle' => 'Enter your login', 'class' => 'text', 'required' => true));
	//Textarea field
	$form->addField('contents', 'textarea', array('title' => 'Description', 'subtitle' => 'Enter a description', 'rows' => 10, 'cols' => 10, 'required' => true));
	$form->addField('comments', 'textarea', array('title' => 'Comments', 'subtitle' => '', 'rows' => 4, 'cols' => 5, 'editor' => false));
	$form->displayPreview('contents'); //Display a preview button for the textarea field(ajax).
	//Radio button field
	$form->addField('choice', 'radio', array('title' => 'Answer', 'optiontitle' => 'Choix1', 'value' => 1, 'checked' => 0));
	$form->addField('choice', 'radio', array('optiontitle' => 'Choix2', 'value' => 2, 'checked' => 1));
	//Checkbox button field
	$form->addField('multiplechoice', 'checkbox', array('title' => 'Answer2', 'optiontitle' => 'Choix3', 'value' => 1));
	$form->addField('multiplechoice', 'checkbox', array('optiontitle' => 'Choix4', 'value' => 2, 'checked' => 1));
	//Select field
	$form->addField('sex', 'select', array('title' => 'Sex', 'optiontitle' => 'Men', 'value' => 1));
	$form->addField('sex', 'select', array('optiontitle' => 'Women', 'value' => 1));
	$form->addField('sex', 'select', array('optiontitle' => '?', 'value' => -1, 'selected' => 1));
	//File field
	$form->addField('avatar', 'file', array('title' => 'Avatar', 'subtitle' => 'Upload a file', 'class' => 'file', 'size' => 30, 'required' => true));
	//Radio button field
	$form->addField('test', 'hidden', array('value' => 1));
	
	echo $form->display();
 * @package helpers
 */ 

define('FIELD_INPUT__TEXT', 'text');
define('FIELD_INPUT__RADIO', 'radio');
define('FIELD_INPUT__CHECKBOX', 'checkbox');
define('FIELD_INPUT__HIDDEN', 'hidden');
define('FIELD_INPUT__FILE', 'file');
define('FIELD__TEXTAREA', 'textarea');
define('FIELD__SELECT', 'select');

class FormHelper
{
	/**
	 * @desc constructor
	 * @param $formName The name of the form.
	 * @param $formTitle The tite displayed for the formular.
	 * @param $formAction The url where the formular send the data.
	 */
	function FormHelper($formName, $formTitle = '', $formAction = '')
	{
		global $LANG;
		
		$this->formName = $formName;
		$this->formTitle = $formTitle;
		$this->formAction = $formAction;
		$this->formSubmit = $LANG['submit'];
	}
	
	/**
	 * @desc Builds the fields objects and store them in the formular.
	 * @param $fieldName
	 * @param $fieldType
	 * @param $arrayOptions
	 * @return unknown_type
	 */
	function addField($fieldName, $fieldType, $arrayOptions = array())
	{
		switch ($fieldType)
		{
			case FIELD_INPUT__TEXT :
				import('helpers/forms/field_input_text');
				$this->formFields[$fieldName] = new FormInputText($fieldName, $arrayOptions);
			break;
			case FIELD_INPUT__HIDDEN :
				import('helpers/forms/field_input_hidden');
				$this->formFields[$fieldName] = new FormInputHidden($fieldName, $arrayOptions);
			break;
			case FIELD_INPUT__FILE :
				import('helpers/forms/field_input_file');
				$this->formFields[$fieldName] = new FormInputFile($fieldName, $arrayOptions);
			break;
			case FIELD__TEXTAREA :
				import('helpers/forms/field_textarea');
				$this->formFields[$fieldName] = new FormTextarea($fieldName, $arrayOptions);
			break;
			case FIELD_INPUT__RADIO :
				import('helpers/forms/field_input_radio');
				if (isset($this->formFields[$fieldName]))
				{	
					$tmpField = new FormInputRadio($fieldName, $arrayOptions);
					$this->formFields[$fieldName]->addOption($tmpField);
				}
				else
				{
					$this->formFields[$fieldName] = new FormInputRadio($fieldName, $arrayOptions);
					$this->formFields[$fieldName]->addOption($this->formFields[$fieldName]);
				}
			break;
			case FIELD_INPUT__CHECKBOX :
				import('helpers/forms/field_input_checkbox');
				if (isset($this->formFields[$fieldName]))
				{	
					$tmpField = new FormInputCheckbox($fieldName, $arrayOptions);
					$this->formFields[$fieldName]->addOption($tmpField);
				}
				else
				{
					$this->formFields[$fieldName] = new FormInputCheckbox($fieldName, $arrayOptions);
					$this->formFields[$fieldName]->addOption($this->formFields[$fieldName]);
				}
			break;
			case FIELD__SELECT :
				import('helpers/forms/field_select');
				if (isset($this->formFields[$fieldName]))
				{	
					$tmpField = new FormSelect($fieldName, $arrayOptions);
					$this->formFields[$fieldName]->addOption($tmpField);
				}
				else
				{
					$this->formFields[$fieldName] = new FormSelect($fieldName, $arrayOptions);
					$this->formFields[$fieldName]->addOption($this->formFields[$fieldName]);
				}
			break;
		}
	}

	/**
	 * @desc Returns the form
	 * @param $Template Optionnal template
	 * @return string
	 */
	function display($Template = false)
	{
		global $LANG;
		
		if (!is_object($Template) || strtolower(get_class($Template)) != 'template')
			$Template = new Template('framework/helper/forms/forms.tpl');
			
		$Template->assign_vars(array(
			'C_DISPLAY_PREVIEW' => $this->display_preview,
			'C_DISPLAY_RESET' => $this->display_reset, 
			'FORMONSUBMIT' => $this->formOnSubmit,
			'FORMCLASS' => $this->formClass,
			'U_FORMACTION' => $this->formAction,
			'L_FORMTITLE' => $this->formTitle,
			'L_FORMNAME' => $this->formName,
			'L_FIELD_CONTENT_PREVIEW' => $this->fieldContentPreview,
			'L_REQUIRED_FIELDS' => $LANG['require'],
			'L_SUBMIT' => $this->formSubmit,
			'L_PREVIEW' => $LANG['preview'],
			'L_RESET' => $LANG['reset'],
		));	
		
		foreach($this->formFields as $Field)
		{
			$Template->assign_block_vars('fields', array(
				'FIELD' => $Field->display(),
			));	
		}
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}
	
	/**
	 * @desc Displays the preview button for textarea fields.
	 * @param $fieldContentPreview The identifier of the textarea.
	 * @param $value True to display, false to hide.
	 */
	function displayPreview($fieldContentPreview, $value = true)
	{
		$this->fieldContentPreview = $fieldContentPreview;
		$this->display_preview = true;
	}
	/**
	 * @desc Displays a reset button for the formular.
	 * @param $value True to display, false to hide.
	 */
	function displayReset($value)
	{
		$this->display_reset = $value;
	}
	
	//Setters
	function setFormTitle($formTitle) { $this->formTitle = $formTitle; }
	function setFormName($formName) { $this->formName = $formName; }
	function setFormSubmit($formSubmit) { $this->formSubmit = $formSubmit; }
	function setFormAction($formAction) { $this->formAction = $formAction; }
	function setFormOnSubmit($formOnSubmit) { $this->formOnSubmit = $formOnSubmit; }
	function setFormClass($formClass) { $this->formClass = $formClass; }
	
	//Getters
	function getFormTitle() { return $this->formTitle; }
	function getFormName() { return $this->formName; }
	function getFormSubmit() { return $this->formSubmit; }
	function getFormAction() { return $this->formAction; }
	function getFormOnSubmit() { return $this->formOnSubmit; }
	function getFormClass() { return $this->formClass; }
	
	var $formFields = array(); //Fields stored
	var $formName = '';
	var $formTitle = '';
	var $formSubmit = '';
	var $formAction = '';
	var $formOnSubmit = ''; //Action performed on submit (javascript).
	var $formClass = 'fieldset_mini';
	var $fieldContentPreview = 'contents'; //Field identifier of textarea for preview.

	var $display_preview = false;
	var $display_reset = true;
}

?>