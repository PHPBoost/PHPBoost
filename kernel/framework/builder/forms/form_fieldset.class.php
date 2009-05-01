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

import('builder/forms/form_fields');

class FormFieldset extends FormBuilder
{
	/**
	 * @desc constructor
	 * @param $form_name The name of the form.
	 * @param $form_title The tite displayed for the form.
	 * @param $form_action The url where the form send the data.
	 */
	function FormFieldset($fieldset_title)
	{
		$this->fieldset_title = $fieldset_title;
	}
	
	/**
	 * @desc Store fields in the fieldset.
	 * @param $field_name
	 * @param $fieldType
	 * @param $arrayOptions
	 * @return unknown_type
	 */
	function add_field($object)
	{
		$field_name = $object->field_id;
		if ($object->has_option) //On vérifie si l'objet peut prendre des options.
		{
			if (isset($this->fieldset_fields[$field_name]))
				$this->fieldset_fields[$field_name]->add_option($object);
			else
			{
				$this->fieldset_fields[$field_name] = $object;
				$this->fieldset_fields[$field_name]->add_option($this->fieldset_fields[$field_name]);
			}
		}
		else
			$this->fieldset_fields[$field_name] = $object;
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
			$Template = new Template('framework/builder/forms/fieldset.tpl');
			
		$Template->assign_vars(array(
			'L_FORMTITLE' => $this->fieldset_title,
			'L_REQUIRED_FIELDS' => $LANG['require'],
		));	
		
		foreach($this->fieldset_fields as $Field)
		{
			$Template->assign_block_vars('fields', array(
				'FIELD' => $Field->display(),
			));	
		}
		
		return $Template->parse(TEMPLATE_STRING_MODE);
	}

	function set_fieldset_title($fieldset_title) { $this->fieldset_title = $fieldset_title; }
	function get_fieldse_title() { return $this->fieldset_title; }
	
	var $fieldset_title = '';
	var $fieldset_fields = '';
}

?>