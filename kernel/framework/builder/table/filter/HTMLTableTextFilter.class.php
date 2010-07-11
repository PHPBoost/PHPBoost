<?php
/*##################################################
 *                         HTMLTableTextFilter.class.php
 *                            -------------------
 *   begin                : February 28, 2010
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
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc
 * @package {@package}
 */
abstract class HTMLTableTextFilter extends AbstractHTMLTableFilter
{
	private $match_regex;

	public function __construct($name, $label, $match_regex = null)
	{
		$this->match_regex = $match_regex;
		$input_text = new FormFieldTextEditor($name, $label, '');
		parent::__construct($name, $input_text);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_value_allowed($value)
	{
		if (empty($this->match_regex) || preg_match($this->match_regex, $value))
		{
			$this->set_value($value);
			return true;
		}
		return false;
	}
}

?>