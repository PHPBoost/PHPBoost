<?php
/*##################################################
 *                             FormFieldFree.class.php
 *                            -------------------
 *   begin                : September 19, 2009
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
 * @desc This class manage free contents fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>template : A template object to personnalize the field</li>
 * 	<li>content : The field html content if you don't use a personnal template</li>
 * </ul>
 * @package {@package}
 */
class FormFieldLabel extends AbstractFormField
{
	public function __construct($value, array $properties = array())
	{
		parent::__construct('', '', $value, $properties);
	}

	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		$template->put('LABEL', $this->get_value());

		return $template;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormFieldLabel.tpl');
	}
 
	protected function get_js_specialization_code()
	{
		return 'field.validate = function() { return true; };' .
			'field.disable = function() { }; field.enable = function() { };' .
			'field.isDisabled = function() { return false; };' . 
			'field.getValue = function() { return ""; };';
	}
}
?>