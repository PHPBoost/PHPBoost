<?php
/*##################################################
 *                             FormFieldHTML.class.php
 *                            -------------------
 *   begin                : April 10, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
class FormFieldHTML extends AbstractFormField
{
	public function __construct($id, $value)
	{
		parent::__construct($id, '', $value, array(), array());
	}

	/**
	 * @return string The html code for the free field.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$template->put_all(array(
			'HTML' => $this->get_value()
		));

		return $template;
	}

	protected function get_default_template()
	{
		return new StringTemplate('{HTML}');
	}
}
?>