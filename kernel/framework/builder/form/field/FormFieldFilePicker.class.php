<?php
/*##################################################
 *                             FormFieldFilePicker.class.php
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
 * @desc This class manage file input fields.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>size : The size for the field</li>
 * </ul>
 * @package builder
 * @subpackage form
 */
class FormFieldFilePicker extends AbstractFormField
{
	private $max_size = 0;
	private $exception = null;

	public function __construct($id, $label, $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, null, $field_options, $constraints);
	}

	/**
	 * @return Template The html code for the file input.
	 */
	function display()
	{
		$template = new Template('framework/builder/form/FormField.tpl');

		$field = '<input name="max_file_size" value="' . $this->get_max_file_size() . '" type="hidden">';
		$field .= '<input type="file" ';
		$field .= 'name="' . $this->get_html_id() . '" ';
		$field .= 'id="' . $this->get_html_id() . '" ';
		$field .= '/>';

		$field .= '<script type="text/javascript"><!--' . "\n";
		$field .= '$("' . $this->get_html_id() . '").form.enctype = "multipart/form-data";' . "\n";
		$field .= '--></script>';

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $field
		));

		return $template;
	}

	private function get_max_file_size()
	{
		if ($this->max_size > 0)
		{
			return $this->max_size;
		}
		else
		{
			return 10000000000;
		}
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'max_size':
					$this->max_size = $value;
					unset($field_options['max_size']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/builder/form/field/AbstractFormField#validate()
	 */
	public function validate()
	{
		try
		{
			$this->retrieve_value();
			return true;
		}
		catch(Exception $ex)
		{
			if ($this->is_required())
			{
				return false;
			}
			else
			{
				return true;
			}
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/builder/form/field/AbstractFormField#retrieve_value()
	 */
	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$file = $request->get_file($this->get_html_id());
		$this->set_value($file);
	}
}

?>