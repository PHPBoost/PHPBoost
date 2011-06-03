<?php
/*##################################################
 *                             MenuInstance.class.php
 *                            -------------------
 *   begin                : October 25, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc
 */
class MenuInstance extends BusinessObject
{    
    private $id;
    private $menu_id;
    private $menu_configuration_id;
    private $block;
    private $position;

	public function get_id()
	{
		return $this->id;
	}

	public function get_menu_id()
	{
		return $this->menu_id;
	}

	public function get_menu_configuration_id()
	{
		return $this->menu_configuration_id;
	}

	public function get_block()
	{
		return $this->block;
	}

	public function get_position()
	{
		return $this->position;
	}

	public function set_id($value)
	{
		$this->id = $value;
	}

	public function set_menu_id($value)
	{
		$this->menu_id = $value;
	}

	public function set_menu_configuration_id($value)
	{
		$this->menu_configuration_id = $value;
	}

	public function set_block($value)
	{
		$this->block = $value;
	}

	public function set_position($value)
	{
		$this->position = $value;
	}
}
?>