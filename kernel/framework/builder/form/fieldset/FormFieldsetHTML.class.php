<?php
/*##################################################
 *                       FormFieldsetHTML.class.php
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
 * @package builder
 * @subpackage form/fieldset
 * @desc
 * @author Régis Viarre <crowkait@phpboost.com>
 */
class FormFieldsetHTML extends AbstractFormFieldset
{
	private $title = '';

	/**
	 * @desc constructor
	 * @param string $name The name of the fieldset
	 */
	public function __construct($name)
	{
		$this->title = $name;
	}

	/**
	 * @desc Return the form
	 * @param Template $Template Optionnal template
	 * @return string
	 */
	public function display()
	{
		$template = new FileTemplate('framework/builder/form/FormFieldset.tpl');

		$template->assign_vars(array(
			'L_FORMTITLE' => $this->title
		));

		//On affiche les champs
		foreach($this->fields as $field)
		{
			$template->assign_block_vars('fields', array(), array(
				'FIELD' => $field->display(),
			));
		}
		return $template;
	}

	/**
	 * @param string $title The fieldset title
	 */
	public function set_title($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string The fieldset title
	 */
	public function get_title()
	{
		return $this->title;
	}
}

?>