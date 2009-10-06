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
 * @package builder
 * @subpackage form
 *
 */
class FormFieldset
{
	private $title = '';
	private $fields = array();
	private $errors = array();
	private $display_required = false;
	
	/**
	 * @desc constructor
	 * @param string $form_name The name of the form.
	 * @param string $form_title The tite displayed for the form.
	 * @param string $form_action The url where the form send the data.
	 */
	public function __construct($title)
	{
		$this->title = $title;
	}
	
	/**
	 * @desc Store fields in the fieldset.
	 * @param FormField $form_field
	 */
	public function add_field($form_field)
	{
		if (isset($this->fields[$form_field->get_id()]))
			$this->throw_error(sprintf('Field with identifier "<strong>%s</strong>" already exists, please chose a different one!', $form_field->get_id()), E_USER_WARNING);
		else
			$this->fields[$form_field->get_id()] = $form_field;
	}

	/**
	 * @desc Return the form
	 * @param Template $Template Optionnal template
	 * @return string
	 */
	public function display($Template = false)
	{
		global $LANG, $Errorh;
		
		if (!is_object($Template) || strtolower(get_class($Template)) != 'template')
			$Template = new Template('framework/builder/forms/fieldset.tpl');
			
		$Template->assign_vars(array(
			'C_DISPLAY_WARNING_REQUIRED_FIELDS' => $this->display_required,
			'L_FORMTITLE' => $this->title,
			'L_REQUIRED_FIELDS' => $LANG['require'],
		));	
		
		//On liste les éventuelles erreurs du fieldset.
		foreach($this->errors as $error) 
		{
			$Template->assign_block_vars('errors', array(
				'ERROR' => $Errorh->display($error['errstr'], $error['errno'])
			));	
		}
		
		//On affiche les champs		
		foreach($this->fields as $Field)
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
		
		return $Template->parse(Template::TEMPLATE_PARSER_STRING);
	}

	/**
	 * @param string $title The fieldset title
	 */
	public function set_title($title) { $this->title = $title; }
	
	/**
	 * @param boolean $display_required
	 */
	public function set_display_required($display_required) { $this->display_required = $display_required; }
	
	/**
	 * @return string The fieldset title
	 */
	public function get_title() { return $this->title; }
	
	/**
	 * @return array All fields in the fieldset.
	 */
	public function get_fields() { return $this->fields; }
	
	/**
	 * @return boolean True if the fieldset has to display a warning for required fields.
	 */
	public function get_display_required() { return $this->display_required; }
	
	/**
	 * @desc Store all erros in the field construct process.
	 * @param string $errstr  Error message description
	 * @param int $errno Error type, use php constants.
	 */	
	private function throw_error($errstr, $errno)
	{
		$this->errors[] = array('errstr' => $errstr, 'errno' => $errno);
	}
}

?>