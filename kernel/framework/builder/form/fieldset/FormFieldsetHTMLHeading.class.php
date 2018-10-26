<?php
/*##################################################
 *                       FormFieldsetHTMLHeading.class.php
 *                            -------------------
 *   begin                : October 26, 2018
 *   copyright            : (C) 2018 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @package {@package}
 * @desc
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class FormFieldsetHTMLHeading extends FormFieldsetHTML
{
	/**
	 * @desc constructor
	 * @param string $name The name of the fieldset
	 */
	public function __construct($id, $name = '', $options = array())
	{
		parent::__construct($id, $name, $options);
	}

	/**
	 * @desc Return the form
	 * @param Template $template Optionnal template
	 * @return string
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$template->put_all(array(
			'C_HEADING' => true,
			'C_TITLE'   => !empty($this->title),
			'L_TITLE'   => $this->title
		));

		$this->assign_template_fields($template);

		return $template;
	}
}

?>