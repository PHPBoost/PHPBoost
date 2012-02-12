<?php
/*##################################################
 *                             form_fieldset.class.php
 *                            -------------------
 *   begin                : May 01, 2009
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

import('builder/form/form_builder');

/**
 * @desc
 * @author Régis Viarre <crowkait@phpboost.com>
 * @abstract
 * @package builder
 * @subpackage form
 *
 */
class FormFieldset
{
	/**
	 * @desc constructor
	 * @param string $form_name The name of the form.
	 * @param string $form_title The tite displayed for the form.
	 * @param string $form_action The url where the form send the data.
	 */
	function FormFieldset($fieldset_title)
	{
		$this->fieldset_title = $fieldset_title;
	}
	
	/**
	 * @desc Store fields in the fieldset.
	 * @param FormField $form_field
	 */
	function add_field($form_field)
	{
		if (isset($this->fieldset_fields[$form_field->field_id]))
			$this->throw_error(sprintf('Field with identifier "<strong>%s</strong>" already exists, please chose a different one!', $form_field->field_id), E_USER_WARNING);
		else
			$this->fieldset_fields[$form_field->field_id] = $form_field;
	}

	/**
	 * @desc Return the form
	 * @param Template $Template Optionnal template
	 * @return string
	 */
	function display($Template = false)
	{
		global $LANG, $Errorh;
		
		if (!is_object($Template) || strtolower(get_class($Template)) != 'template')
			$Template = new Template('framework/builder/forms/fieldset.tpl');
			
		$Template->assign_vars(array(
			'C_DISPLAY_WARNING_REQUIRED_FIELDS' => $this->fieldset_display_required,
			'L_FORMTITLE' => $this->fieldset_title,
			'L_REQUIRED_FIELDS' => $LANG['require'],
		));	
		
		//On liste les éventuelles erreurs du fieldset.
		foreach($this->fieldset_errors as $error) 
		{
			$Template->assign_block_vars('errors', array(
				'ERROR' => $Errorh->display($error['errstr'], $error['errno'])
			));	
		}
		
		//On affiche les champs		
		foreach($this->fieldset_fields as $Field)
		{
			foreach($Field->get_errors() as $error) //On liste les éventuelles erreurs du champs.
			{
				$Template->assign_block_vars('errors', array(
					'ERROR' => $Errorh->display($error['errstr'], $error['errno'])
				));	
			}
			
			$Template->assign_block_vars('fields', array(
				'FIELD' => $Field->display(),
			));	
		}
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}

	/**
	 * @desc Store all erros in the field construct process.
	 * @param string $errstr  Error message description
	 * @param int $errno Error type, use php constants.
	 */	
	function throw_error($errstr, $errno)
	{
		$this->fieldset_errors[] = array('errstr' => $errstr, 'errno' => $errno);
	}
	
	/**
	 * @desc Display a preview button for the specified field.
	 * @param string $fieldset_title The fieldset title
	 */
	function display_preview_button($field_identifier_preview) { $this->field_identifier_preview = $field_identifier_preview; }
	
	/**
	 * @param string $fieldset_title The fieldset title
	 */
	function set_title($fieldset_title) { $this->fieldset_title = $fieldset_title; }
	
	/**
	 * @param boolean $fieldset_display_required
	 */
	function set_display_required($fieldset_display_required) { $this->fieldset_display_required = $fieldset_display_required; }
	
	/**
	 * @return string The fieldset title
	 */
	function get_title() { return $this->fieldset_title; }
	
	/**
	 * @return array All fields in the fieldset.
	 */
	function get_fields() { return $this->fieldset_fields; }
	
	/**
	 * @return boolean True if the fieldset has to display a warning for required fields.
	 */
	function get_display_required() { return $this->fieldset_display_required; }
	
	var $fieldset_title = '';
	var $fieldset_fields = array();
	var $fieldset_errors = array();
	var $fieldset_display_required = false;
}

?>