<?php
/*##################################################
 *                    FormFieldBooleanInformation.class.php
 *                            -------------------
 *   begin                : August 8, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class FormFieldBooleanInformation extends FormFieldFree
{
	/**
	 * @param bool $value
	 */
	public function __construct($id, $label, $value, array $properties)
	{
		parent::__construct($id, $label, $value, $properties);
	}

	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $this->get_html_value()
		));

		return $template;
	}
	
	protected function get_html_value()
	{
		return '<i class="' . ($this->get_value() ? 'fa fa-success' : 'fa fa-error') . ' fa-2x"></i>';
	}
}
?>