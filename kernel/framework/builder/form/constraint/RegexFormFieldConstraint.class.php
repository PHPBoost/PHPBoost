<?php
/*##################################################
 *                         RegexFormFieldConstraint.class.php
 *                            -------------------
 *   begin                : December 19, 2009
 *   copyright            : (C) 2009 Régis Viarre, Loic Rouchon
 *   email                : crowkait@phpboost.com, loic.rouchon@phpboost.com
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
 * @author Régis Viarre <crowkait@phpboost.com>, Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc
 * @package builder
 * @subpackage form/constraint
 */
class RegexFormFieldConstraint implements FormFieldConstraint
{
	private $js_message;
	private $php_regex;
	private $js_regex;

	public function __construct($js_message, $php_regex, $js_regex = '')
	{
		$this->js_message = $js_message;
		$this->php_regex = $php_regex;
		if (!empty($js_regex))
		{
			$this->js_regex = $js_regex;
		}
		else
		{
			$this->js_regex = $this->php_regex;
		}

	}

	public function validate(FormField $field)
	{
		$value = $field->get_value($name);
		return preg_match($this->php_regex, $value) > 0;
	}

	public function get_onblur_validation(FormField $field)
	{
		return 'regexFormFieldOnblurValidator();';
	}

	public function get_onsubmit_validation(FormField $field)
	{
		return 'regexFormFieldOnsubmitValidator("' . $field->get_id() .
			'", ' . to_js_string($this->js_regex) . ', ' . to_js_string($this->js_message) . ')';
	}
}

?>