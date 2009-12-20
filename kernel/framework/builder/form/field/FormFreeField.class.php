<?php
/*##################################################
 *                             field_free_field.class.php
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
 * @package builder
 * @subpackage form
 */
class FormFreeField extends FormField
{
	private $content = ''; //Content of the free field
	private $template = ''; //Optionnal template
	
	public function __construct($field_id, $field_options)
	{
		parent::__construct($field_id, '', $field_options);
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'template' :
					$this->template = $value;
				break;
				case 'content' :
					$this->content = $value;
				break;
				default :
					$this->throw_error(sprintf('Unsupported option %s with field ' . __CLASS__, $attribute), E_USER_NOTICE);
			}
		}
	}
	
	/**
	 * @return string The html code for the free field.
	 */
	public function display()
	{
		if (is_object($this->template) && strtolower(get_class($this->template)) == 'template') //Optionnal template
			$Template = $this->template;
		else
			$Template = new Template('framework/builder/forms/field.tpl');
			
		$Template->assign_vars(array(
			'ID' => $this->id,
			'FIELD' => $this->content,
			'L_FIELD_TITLE' => $this->title,
			'L_EXPLAIN' => $this->sub_title,
			'L_REQUIRE' => $this->required ? '* ' : ''
		));	
		
		return $Template->parse(Template::TEMPLATE_PARSER_STRING);
	}
}

?>