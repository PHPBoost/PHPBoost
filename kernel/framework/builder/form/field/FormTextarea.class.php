<?php
/*##################################################
 *                             field_input_text.class.php
 *                            -------------------
 *   begin                : April 28, 2009
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
 * @desc This class manage multi-line text fields.
 * @package builder
 * @subpackage form
 */
class FormTextarea extends FormField
{
	private $rows = 10; //Rows for the textarea.
	private $cols = 47; //Cols for the textarea.
	private $editor = true; //Allow to hide the editor.
	private $forbidden_tags = array(); //Forbiddend tags in the content.
	
	public function __construct($field_id, $value, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($field_id, $value, $field_options, $constraints);
		$this->compute_fields_options($field_options);
	}
	
	/**
	 * @return string The html code for the textarea.
	 */
	public function display()
	{
		$Template = new Template('framework/builder/forms/field_extended.tpl');
			
		$field = '<textarea type="text" ';
		$field .= 'rows="' . $this->rows . '" ';
		$field .= 'cols="' . $this->cols . '" ';
		$field .= 'name="' . $this->name . '" ';
		$field .= !empty($this->id) ? 'id="' . $this->id . '" ' : '';
		$field .= !empty($this->css_class) ? 'class="' . $this->css_class . '"> ' : '>';
		$field .= $this->value;
		$field .= '</textarea>';
		
		$Template->assign_vars(array(
			'ID' => $this->id,
			'FIELD' => $field,
			'KERNEL_EDITOR' => $this->editor ? display_editor($this->id, $this->forbidden_tags) : '',
			'L_FIELD_TITLE' => $this->title,
			'L_EXPLAIN' => $this->sub_title,
			'L_REQUIRE' => $this->required ? '* ' : ''
		));	
		
		return $Template->parse(Template::TEMPLATE_PARSER_STRING);
	}

	private function compute_fields_options(array $field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'rows' :
					$this->rows = $value;
				break;
				case 'cols' :
					$this->cols = $value;
				break;
				case 'editor' :
					$this->editor = $value;
				break;
				case 'forbiddentags' :
					$this->forbidden_tags = $value;
				break;
				default :
					throw new FormBuilderException(sprintf('Unsupported option %s with field ' . __CLASS__, $attribute));
			}
		}
	}
}

?>