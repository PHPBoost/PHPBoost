<?php
/*##################################################
 *                       FormMultiLineTextEdit.class.php
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
class FormMultiLineTextEdit extends AbstractFormField
{
	protected $rows = 5;
	protected $cols = 40;

	public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	/**
	 * @return string The html code for the textarea.
	 */
	public function display()
	{
		$template = new Template('framework/builder/form/FormMultiLineTextEdit.tpl');
			
		$this->assign_common_template_variables($template);
		$this->assign_textarea_template_variables($template);
		
		return $template;
	}
	
	private function assign_textarea_template_variables(Template $template)
	{
		$template->assign_vars(array(
			'ROWS' => $this->rows,
			'COLS' => $this->cols
		));
	}
	
	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'rows':
					$this->rows = $value;
					unset($field_options['rows']);
					break;
				case 'cols':
					$this->cols = $value;
					unset($field_options['cols']);
					break;
			}
		}
		parent::compute_options($field_options);
	}
}

?>