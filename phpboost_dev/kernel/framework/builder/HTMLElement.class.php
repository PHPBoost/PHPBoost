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
 * @package builder
 */
abstract class HTMLElement
{
	private $css_style = '';
	private $css_classes = array();
	
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
		$this->css_style = rtrim($this->css_style, ';') . ';' . trim($style, ';');
	}
	
	public function has_css_classes()
	{
		return !empty($this->css_classes);
	}
	
	public function get_css_classes()
	{
		return $this->css_classes;
	}
	
	public function set_css_classes(array $classes)
	{
		$this->css_classes = $classes;
	}
}

?>