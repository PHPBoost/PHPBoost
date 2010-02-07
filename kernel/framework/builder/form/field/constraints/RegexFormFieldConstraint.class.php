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
	private $js_options;

	public function __construct($php_regex, $js_regex = '', $js_message = '')
	{
		if (empty($js_regex))
		{
			$js_regex = $php_regex;
		}
		$this->parse_js_regex($js_regex);
		$this->php_regex = $php_regex;
		
		if (empty($js_message))
		{
			$js_message = LangLoader::get_message('doesnt_match_regex', 'builder-form-Validator');
		}
		$this->js_message = TextHelper::to_js_string($js_message);

	}
	
	private function parse_js_regex($regex)
	{
		$delimiter = $regex[0];
		$end_delimiter_position = strrpos($regex, $delimiter);
		$js_regex = substr($regex, 1, $end_delimiter_position - 1);
		$js_options = substr($regex, $end_delimiter_position + 1);
		$this->js_regex = '\'' . str_replace('\'', '\\\'', $js_regex) . '\'';
		$this->js_options = '\'' . str_replace('\'', '\\\'', $js_options) . '\'';
	}

	public function validate(FormField $field)
	{
		$value = $field->get_value();
		return preg_match($this->php_regex, $value) > 0;
	}

	public function get_js_validation(FormField $field)
	{
		return 'regexFormFieldValidator(' . TextHelper::to_js_string($field->get_html_id()) .
			', ' . $this->js_regex . ', ' . $this->js_options . ', ' . $this->js_message . ')';
	}
}

?>