<?php
/*##################################################
 *                             FormFieldset.class.php
 *                            -------------------
 *   begin                : May 01, 2009
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
 * @desc
 * @author Régis Viarre <crowkait@phpboost.com>
 * @package builder
 * @subpackage form
 */
class FormFieldset implements ValidableFormComponent
{
	private $title = '';
	private $form_name = '';
	private $fields = array();
	
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
	
	public function set_form_name($form_name)
	{
		$this->form_name = $form_name;
	}
	
	/**
	 * @desc Store fields in the fieldset.
	 * @param FormField $form_field
	 */
	public function add_field($form_field)
	{
		if (isset($this->fields[$form_field->get_id()]))
		{
			$this->throw_error(sprintf('Field with identifier "<strong>%s</strong>" already exists, please chose a different one!', $form_field->get_id()), E_USER_WARNING);
		}
		else
		{
			$this->fields[$form_field->get_id()] = $form_field;
		}
	}
	
	public function validate()
	{
		$validation_result = true;
		foreach ($this->fields as $field)
		{
			$validation_result = $validation_result && $field->validate();
		}
		return $validation_result;	
	}

	/**
	 * @desc Return the form
	 * @param Template $Template Optionnal template
	 * @return string
	 */
	public function display($template = false)
	{
		global $LANG;
		
		if (!is_object($template) || strtolower(get_class($template)) != 'template')
		{
			$template = new Template('framework/builder/forms/fieldset.tpl');
		}
			
		$template->assign_vars(array(
			'L_FORMTITLE' => $this->title,
			'L_REQUIRED_FIELDS' => $LANG['require'],
		));
		
		//On affiche les champs		
		foreach($this->fields as $field)
		{
			$field->prefix_id($this->form_name);
			$template->assign_block_vars('fields', array(
				'FIELD' => $field->display(),
			));	
		}
		
		return $template->parse(Template::TEMPLATE_PARSER_STRING);
	}
	
	public function get_onsubmit_validations()
	{
		$validations = array();
		foreach ($this->fields as $field)
		{
			$validations[] = $field->get_onsubmit_validations();
		}
		return $validations;
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
	 * @return bool
	 */
	public function has_field($field_id) { return isset($this->fields[$field_id]); }
	
	/**
	 * @return FormField
	 */
	public function get_field($field_id) { return $this->fields[$field_id]; }
}

?>