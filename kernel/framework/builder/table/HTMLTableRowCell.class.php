<?php
/*##################################################
 *                             HTMLTableRowCell.class.php
 *                            -------------------
 *   begin                : December 21, 2009
 *   copyright            : (C) 2009 Loic Rouchon
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
 * @desc This class allows you to manage easily html tables.
 * @package {@package}
 */
class HTMLTableRowCell extends AbstractHTMLElement
{
	private $value;
	private $colspan = 1;
	
	public function __construct($value, $css_class = '', $id = '')
	{
		if ($value instanceof HTMLElement)
		{
			$value = $value->display();
		}
		
		$this->value = $value;
		$this->css_class = $css_class;
		$this->id = $id;
	}
	
	public function get_value()
	{
		return $this->value;
	}
	
	public function is_multi_column()
	{
		return $this->colspan > 1;
	}
	
	public function get_colspan()
	{
		return $this->colspan;
	}
	
	public function set_colspan($colspan)
	{
		$this->colspan = $colspan;
	}
}
?>