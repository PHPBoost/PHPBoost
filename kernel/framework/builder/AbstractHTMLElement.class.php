<?php
/*##################################################
 *                             HTMLElement.class.php
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
 * @desc This class allows you to manage easily html elements.
 * @package {@package}
 */
abstract class AbstractHTMLElement implements HTMLElement
{
	protected $css_class = '';
	protected $css_style = '';
	protected $id = '';
	
	public function has_css_style()
	{
		return !empty($this->css_style);
	}
	
	public function get_css_style()
	{
		return $this->css_style;
	}
	
	public function set_css_style($style)
	{
		$this->css_style = $style;
	}

	public function add_css_style($style)
	{
		$this->css_style .= ' ' . $style;
	}
	
	public function has_css_class()
	{
		return !empty($this->css_class);
	}
	
	public function get_css_class()
	{
		return $this->css_class;
	}
	
	public function set_css_class($class)
	{
		$this->css_class = $class;
	}

	public function add_css_class($class)
	{
		$this->css_class .= ' ' . $class;
	}
	
	public function has_id()
	{
		return !empty($this->id);
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_id($id)
	{
		$this->id = $id;
	}

	public function display(){}
}
?>